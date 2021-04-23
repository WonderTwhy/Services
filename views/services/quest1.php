<?
use app\models\Services;
use app\models\Services_groups;
use yii\helpers\Html;
?>   
  
<link rel='stylesheet' href='/web/css/Services.css'>
<!-- <link rel='stylesheet' href='/css/Services.css'> -->

<?
$servicesTableArray = (new \yii\db\Query())
->select(['id' ,'nameService', 'priceService', 'serviceInfo', 'idServiceGroup'])
->from('services')
->limit(10)
->all();

$servicesGroupsTableArray = (new \yii\db\Query())
->select(['id' ,'nameServiceGroup'])
->from('services_groups')
->limit(10)
->all();
?>




<div>
  <div id="mainContainer" class="services">
    <div id="groupList" class="services__groups-container">
      <div id="serviceSearch" class="services__search-container">
        <input id="searchInput" type="text" class="services__search-input">
        <img src="/web/img/search.png" alt="" class = 'icon'>
      </div>
      <ul class="services__list">
      <?php 
        $newId = 1;
        foreach ($servicesGroupsTableArray as $items): ?>
		      <li id="singleService" class="services__list-container">
            <button id="services_button_<?=$items["id"]?>" class='services__list-button' onclick="getServices(<?= $items['id'] ?>);" service-group='<?=$items["id"]?>'><?=$items["nameServiceGroup"]?>
            </button>
            <div id="services_list_<?=$items["id"]?>" class="services__list-cont"></div>
          </li>
          <? $newId++; ?>
	    <?php endforeach; ?>
      </ul> 
    </div>
    <div id="servicesContainer" class="services__result-container">
          
    </div>
  </div>
</div>


<script>
const servicesTableArray = <?=json_encode($servicesTableArray)?>;
const servicesGroupsTableArray = <?=json_encode($servicesGroupsTableArray)?>;

const mainContainer = document.getElementById("mainContainer");
const servicesContainer = document.getElementById("servicesContainer");
const buttonsContainers = document.querySelectorAll(".services__list-cont");
const searchInput = document.getElementById("searchInput");
const groupButtons = document.querySelectorAll(".services__list-button");
const groupList = document.getElementById("groupList");
const serviceSearch = document.getElementById("serviceSearch");

const maxElementHeight = 50;
const collapseWidth = 991;
var mobileScreen = false;


function fitMedia() {
  var sumOfHeight = 0;
  
  groupButtons.forEach(function(elem, key){
  if(elem.style.display != 'none' && mobileScreen == true){
    sumOfHeight += elem.offsetHeight;}
}) 
  if(mobileScreen == true)
    mainContainer.style.height = sumOfHeight + serviceSearch.offsetHeight + 30; 
}

function checkTypeScreen() {
  if (mainContainer.offsetWidth <= collapseWidth) mobileScreen = true;
  else mobileScreen = false;
}

function checkEmptyServicesContainer() {
  if (!servicesContainer.childNodes.length) {
    const infoBlock = document.createElement("div");
    infoBlock.classList.add("services__empty");
    infoBlock.innerText = "Здесь будет отображаться информация об услугах";
    servicesContainer.append(infoBlock);
  }
}

function createServiceElement(parent, value) {
  const showBlockWrap = document.createElement("div");
        showBlockWrap.classList.add("services__result-item-container");
        parent.append(showBlockWrap);
        
        const resultItemRow = document.createElement("div");
        resultItemRow.classList.add("services__container-row");
        showBlockWrap.append(resultItemRow);

        const resultItemCol_1 = document.createElement("div");
        resultItemCol_1.classList.add("services__container-leftbar");
        resultItemRow.append(resultItemCol_1);

        const resultItemTitle = document.createElement("a");
        resultItemTitle.classList.add("services__container-title");
        resultItemTitle.innerText = value["nameService"];
        resultItemCol_1.append(resultItemTitle);

        const resultItemText = document.createElement("p");
        resultItemText.classList.add("services__container-info");
        resultItemText.innerText = value["serviceInfo"];
        resultItemCol_1.append(resultItemText);
        
        collapseText(resultItemText);

        const resultItemCol_2 = document.createElement("div");
        resultItemCol_2.classList.add("services__container-rightbar");
        resultItemRow.append(resultItemCol_2,);

        const resultItemPrice = document.createElement("span");
        resultItemPrice.classList.add("services__container-price");
        resultItemPrice.innerText = value["priceService"] + ' руб';
        resultItemCol_2.append(resultItemPrice);

}

function clearAllServices() {
  if (servicesContainer) servicesContainer.innerHTML = "";

  if (buttonsContainers) {
    buttonsContainers.forEach(function(elem, key) {elem.innerHTML = "";});
  }
}

function getServices(id = null) {
  const button = document.getElementById(`services_button_${id}`);
  const list = document.getElementById(`services_list_${id}`);

  const lastActiveButton = document.querySelector(".services__list-button.active");

  if (lastActiveButton) lastActiveButton.classList.remove('active');

  clearAllServices();

  if (button && servicesContainer && id) {
    const serviceGroupId = button.getAttribute("service-group");

    button.classList.add('active');

    if (lastActiveButton == button && list) {
      list.innerHTML = "";
      button.classList.remove('active');
    }
    else {
      servicesTableArray.forEach(function(value, key) {
      if (value["idServiceGroup"] == serviceGroupId) {
        if (mobileScreen && list) createServiceElement(list, value);
        else {
          createServiceElement(servicesContainer, value);
        }
      }
    });
    }

    checkEmptyServicesContainer();
  }

}

clearAllServices();
checkTypeScreen();
checkEmptyServicesContainer();
fitMedia();

window.addEventListener("resize", () => {
  checkTypeScreen();
  getServices();
});

searchInput.addEventListener("change", () => {
  var searchText = searchInput.value.trim();

  [].slice.call(groupButtons).forEach(function(elem) {
    const elemText = elem.innerText.toLowerCase();
    if (elemText.indexOf(searchText.toLowerCase()) == 0 || searchText == "") {
      elem.style.display = "";
    }
    else elem.style.display = "none";
  }); 
  fitMedia();
});

function collapseText(object) {
  if (!object) return;

  const objectHeight = object.offsetHeight;
  
  if (objectHeight >= maxElementHeight) {
    object.setAttribute("max-height", objectHeight);
    object.style.maxHeight = maxElementHeight + "px";

    const collapseButton = document.createElement("button");
    collapseButton.innerText = "Подробнее";
    collapseButton.classList.add("service__text-collapse");
    object.parentNode.append(collapseButton);

    collapseButton.addEventListener("click", e => {
      e.preventDefault();
      if (object.offsetHeight == maxElementHeight) {
        if (object.hasAttribute("max-height")) object.style.maxHeight = object.getAttribute("max-height") + "px";
        else object.style.maxHeight = "max-content";
      }
      else {
        object.style.maxHeight = maxElementHeight + "px";
      }
    });

  }
}

</script>


