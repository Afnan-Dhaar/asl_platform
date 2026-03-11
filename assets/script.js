console.log("SCRIPT LOADED");

function showToast(message,type){

let toast=document.getElementById("toast");

toast.innerText=message;

toast.style.background =
type==="success" ? "#2ecc71" :
type==="error" ? "#e74c3c" :
"#333";

toast.style.display="block";

setTimeout(()=>{
toast.style.display="none";
},3000);

}


// ===============================
// HELP MODAL FUNCTIONS
// ===============================

function openHelp(){
document.getElementById("helpModal").classList.add("show");
}

function closeHelp(){
document.getElementById("helpModal").classList.remove("show");
}


// ==========================================
// OPEN ASSET MODAL (AJAX FETCH SINGLE ASSET)
// ==========================================

function openAssetModal(id){

console.log("Opening modal for asset:", id);

fetch("ajax.php",{
method:"POST",
headers:{
"Content-Type":"application/x-www-form-urlencoded"
},
body:"action=get_asset&id="+id
})

.then(res=>res.json())

.then(data=>{

if(!data){
alert("Asset not found");
return;
}

let imageHTML = "";
let documentHTML = "";

if(data.image){
imageHTML = `
<img src="uploads/images/${data.image}"
style="width:100%;max-height:250px;object-fit:cover;border-radius:10px;margin-bottom:15px;">
`;
}

if(data.document){
documentHTML = `
<br><br>
<a class="view-btn"
href="uploads/documents/${data.document}"
target="_blank">
Download Land Document
</a>
`;
}

let html = `

${imageHTML}

<h2>${data.name}</h2>

<p><strong>Asset Code:</strong> ${data.asset_code}</p>
<p><strong>Total Lands:</strong> ${data.total_lands}</p>
<p><strong>Land Area:</strong> ${data.land_area} m²</p>
<p><strong>Location:</strong> ${data.location}</p>
<p><strong>City:</strong> ${data.city}</p>
<p><strong>Status:</strong> ${data.status}</p>
<p><strong>Valuation:</strong> ${data.valuation}</p>
<p><strong>Description:</strong> ${data.description}</p>

<br>

<a class="view-btn"
target="_blank"
href="https://www.google.com/maps?q=${data.latitude},${data.longitude}">
View Location on Map
</a>

${documentHTML}

`;

document.getElementById("modalContent").innerHTML = html;

document.getElementById("assetModal").classList.add("show");

})

.catch(err=>{
console.error("Modal error:",err);
alert("Error loading asset");
});

}


function closeModal(){
document.getElementById("assetModal").classList.remove("show");
}


// ==========================================
// APPLY FILTER (AJAX)
// ==========================================

function applyFilter(){

let city = document.getElementById("filterCity").value;
let status = document.getElementById("filterStatus").value;

fetch("ajax.php", {
method: "POST",
headers: { "Content-Type": "application/x-www-form-urlencoded" },
body: "action=filter_assets&city=" + encodeURIComponent(city) +
"&status=" + encodeURIComponent(status)
})
.then(response => response.text())
.then(data => {

document.getElementById("cardsContainer").innerHTML = data;

});

}


// ==========================================
// LOAD ALL CARDS ON HOME PAGE
// ==========================================

function loadAllCards(){

showLoader();

fetch("ajax.php", {
method: "POST",
headers: { "Content-Type": "application/x-www-form-urlencoded" },
body: "action=filter_assets"
})
.then(response => response.text())
.then(data => {

document.getElementById("homeCardsContainer").innerHTML = data;
document.querySelector(".view-btn").style.display = "none";

hideLoader();

});

}


function showLoader(){
document.getElementById("globalLoader").style.display = "flex";
}

function hideLoader(){
document.getElementById("globalLoader").style.display = "none";
}


// ==========================================
// PASSWORD TOGGLE
// ==========================================

function togglePassword(inputId, iconWrapper) {

const input = document.getElementById(inputId);

const eyeIcon = `
<svg xmlns="http://www.w3.org/2000/svg"
width="20" height="20" viewBox="0 0 24 24"
fill="none" stroke="currentColor"
stroke-width="2" stroke-linecap="round"
stroke-linejoin="round">
<path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7S2 12 2 12z"/>
<circle cx="12" cy="12" r="3"/>
</svg>
`;

const eyeOffIcon = `
<svg xmlns="http://www.w3.org/2000/svg"
width="20" height="20" viewBox="0 0 24 24"
fill="none" stroke="currentColor"
stroke-width="2" stroke-linecap="round"
stroke-linejoin="round">
<path d="M10.733 5.076a10.744 10.744 0 0 1 11.205 6.575"/>
<path d="m2 2 20 20"/>
</svg>
`;

if (input.type === "password") {
input.type = "text";
iconWrapper.innerHTML = eyeIcon;
} else {
input.type = "password";
iconWrapper.innerHTML = eyeOffIcon;
}

}


// ==========================================
// ADD LAND MODAL
// ==========================================

function openAddLandModal(){
let modal = document.getElementById("addLandModal");

if(modal){
modal.classList.add("show");
}
}

function closeAddLandModal(){
document.getElementById("addLandModal").classList.remove("show");
}


// ==========================================
// DELETE LAND
// ==========================================

function deleteLand(id){

if(confirm("Delete this land?")){
window.location="delete_land.php?id="+id;
}

}


// ==========================================
// SEARCH LAND
// ==========================================

function searchLand(){

let input=document.getElementById("searchLand").value.toLowerCase();

let cards=document.querySelectorAll("#landsContainer .card");

cards.forEach(card=>{

let text=card.innerText.toLowerCase();

card.style.display=text.includes(input) ? "block":"none";

});

}


// ==========================================
// EDIT LAND MODAL
// ==========================================

function editLand(
id,
name,
asset_code,
total_lands,
land_area,
location,
city,
status,
valuation,
description,
latitude,
longitude,
image,
document
){

document.getElementById("edit_id").value = id;

document.getElementById("edit_name").value = name;
document.getElementById("edit_asset_code").value = asset_code;

document.getElementById("edit_total_lands").value = total_lands;
document.getElementById("edit_land_area").value = land_area;

document.getElementById("edit_location").value = location;
document.getElementById("edit_city").value = city;

document.getElementById("edit_status").value = status;

document.getElementById("edit_valuation").value = valuation;

document.getElementById("edit_description").value = description;

document.getElementById("edit_lat").value = latitude;
document.getElementById("edit_long").value = longitude;

document.getElementById("editLandModal").classList.add("show");

}


function closeEditLandModal(){

const modal = document.getElementById("editLandModal");

if(modal){
modal.classList.remove("show");
}

}

function openEditLand(btn){

document.getElementById("edit_id").value = btn.dataset.id;

document.getElementById("edit_name").value = btn.dataset.name;
document.getElementById("edit_asset_code").value = btn.dataset.asset_code;

document.getElementById("edit_total_lands").value = btn.dataset.total_lands;
document.getElementById("edit_land_area").value = btn.dataset.land_area;

document.getElementById("edit_location").value = btn.dataset.location;
document.getElementById("edit_city").value = btn.dataset.city;

document.getElementById("edit_status").value = btn.dataset.status;

document.getElementById("edit_valuation").value = btn.dataset.valuation;

document.getElementById("edit_description").value = btn.dataset.description;

document.getElementById("edit_lat").value = btn.dataset.lat;
document.getElementById("edit_long").value = btn.dataset.long;

document.getElementById("editLandModal").classList.add("show");

}