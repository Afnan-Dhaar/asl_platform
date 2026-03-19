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

function openAssetModal(id,page="home"){

console.log("Opening modal for asset:", id);

fetch("/asl_platform/ajax.php",{
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
let requestButtons = "";
let descriptionHTML = "";

if(data.image){
imageHTML = `
<img src="/asl_platform/uploads/images/${data.image}"
style="width:100%;max-height:250px;object-fit:cover;border-radius:10px;margin-bottom:15px;">
`;
}

if(data.document){
documentHTML = `
<br><br>
<a class="view-btn"
href="/asl_platform/uploads/documents/${data.document}"
target="_blank">
Download Land Document
</a>
`;
}

/* show description only on normal pages */

if(page !== "mylands"){
descriptionHTML = `
<p><strong>Description:</strong> ${data.description}</p>
`;
}

/* show buy/rent only on normal pages */

if(page !== "mylands"){
requestButtons = `



<button class="buy-btn" onclick="sendRequest(${data.id},'buy')">
Buy Land
</button>

<button class="rent-btn" onclick="sendRequest(${data.id},'rent')">
Rent Land
</button>

<textarea id="requestMessage"
placeholder="Add message for admin (optional)"
style="width:100%;height:50px;margin-top:10px;border-radius:8px;"></textarea>

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

${descriptionHTML}

<br>

<a class="view-btn"
target="_blank"
href="https://www.google.com/maps?q=${data.latitude},${data.longitude}">
View Location on Map
</a>

${documentHTML}

${requestButtons}

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

let name = document.getElementById("filterName").value;
let city = document.getElementById("filterCity").value;
let code = document.getElementById("filterCode").value;
let status = document.getElementById("filterStatus").value;
let minArea = document.getElementById("filterMinArea").value;
let maxArea = document.getElementById("filterMaxArea").value;
let minPrice = document.getElementById("filterMinPrice").value;
let maxPrice = document.getElementById("filterMaxPrice").value;

fetch("/asl_platform/ajax.php", {
method: "POST",
headers: { "Content-Type": "application/x-www-form-urlencoded" },
body:
"action=filter_assets" +
"&name=" + encodeURIComponent(name) +
"&city=" + encodeURIComponent(city) +
"&code=" + encodeURIComponent(code) +
"&status=" + encodeURIComponent(status) +
"&minArea=" + encodeURIComponent(minArea) +
"&maxArea=" + encodeURIComponent(maxArea) +
"&minPrice=" + encodeURIComponent(minPrice) +
"&maxPrice=" + encodeURIComponent(maxPrice)
})
.then(res => res.text())
.then(data => {

document.getElementById("cardsContainer").innerHTML = data;

updateFilterChips();

});
}
// Live filtering (no button)
document.querySelectorAll(
"#filterName, #filterCity, #filterCode, #filterStatus, #filterMinArea, #filterMaxArea, #filterMinPrice, #filterMaxPrice"
)
.forEach(el => {
el.addEventListener("input", applyFilter);
});

function resetFilters(){

document.querySelectorAll(".filter-box input, .filter-box select")
.forEach(el => el.value = "");

applyFilter();
}
function updateFilterChips(){

let chipsHTML = "";

// helper
function createChip(label, value, field){
return `<span class="chip">
${label}: ${value}
<span class="chip-close" onclick="removeFilter('${field}')">×</span>
</span>`;
}

// values
let name = document.getElementById("filterName").value;
let city = document.getElementById("filterCity").value;
let code = document.getElementById("filterCode").value;
let status = document.getElementById("filterStatus").value;
let minArea = document.getElementById("filterMinArea").value;
let maxArea = document.getElementById("filterMaxArea").value;
let minPrice = document.getElementById("filterMinPrice").value;
let maxPrice = document.getElementById("filterMaxPrice").value;

// build chips
if(name) chipsHTML += createChip("Name", name, "filterName");
if(city) chipsHTML += createChip("City", city, "filterCity");
if(code) chipsHTML += createChip("Code", code, "filterCode");
if(status) chipsHTML += createChip("Status", status, "filterStatus");
if(minArea) chipsHTML += createChip("Min Area", minArea, "filterMinArea");
if(maxArea) chipsHTML += createChip("Max Area", maxArea, "filterMaxArea");
if(minPrice) chipsHTML += createChip("Min Price", minPrice, "filterMinPrice");
if(maxPrice) chipsHTML += createChip("Max Price", maxPrice, "filterMaxPrice");

document.getElementById("filterChips").innerHTML = chipsHTML;
}

function removeFilter(field){

document.getElementById(field).value = "";

applyFilter();
}
// ==========================================
// LOAD ALL CARDS ON HOME PAGE
// ==========================================

function loadAllCards(){

showLoader();

fetch("/asl_platform/ajax.php", {
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

document.addEventListener("DOMContentLoaded",function(){

loadCityChart();
loadStatusChart();
loadMonthlyChart();
loadUserChart();

});


function loadCityChart(){

fetch("/asl_platform/ajax.php",{
method:"POST",
headers:{'Content-Type':'application/x-www-form-urlencoded'},
body:"action=lands_per_city"
})
.then(res=>res.json())
.then(data=>{

let labels=data.map(d=>d.city);
let values=data.map(d=>d.total);

new Chart(document.getElementById("cityChart"),{

type:"pie",

data:{
labels:labels,
datasets:[{
data:values
}]
}

});

});
}



function loadStatusChart(){

fetch("/asl_platform/ajax.php",{
method:"POST",
headers:{'Content-Type':'application/x-www-form-urlencoded'},
body:"action=lands_by_status"
})
.then(res=>res.json())
.then(data=>{

let labels=data.map(d=>d.status);
let values=data.map(d=>d.total);

new Chart(document.getElementById("statusChart"),{

type:"doughnut",

data:{
labels:labels,
datasets:[{
data:values
}]
}

});

});
}



function loadMonthlyChart(){

fetch("/asl_platform/ajax.php",{
method:"POST",
headers:{'Content-Type':'application/x-www-form-urlencoded'},
body:"action=lands_monthly"
})
.then(res=>res.json())
.then(data=>{

let labels=data.map(d=>"Month "+d.month);
let values=data.map(d=>d.total);

new Chart(document.getElementById("monthlyChart"),{

type:"bar",

data:{
labels:labels,
datasets:[{
label:"Lands Added",
data:values
}]
}

});

});
}



function loadUserChart(){

fetch("/asl_platform/ajax.php",{
method:"POST",
headers:{'Content-Type':'application/x-www-form-urlencoded'},
body:"action=users_growth"
})
.then(res=>res.json())
.then(data=>{

let labels=data.map(d=>"Month "+d.month);
let values=data.map(d=>d.total);

new Chart(document.getElementById("userChart"),{

type:"line",

data:{
labels:labels,
datasets:[{
label:"Users",
data:values
}]
}

});

});
}

let currentMessageEmail="";

function viewMessage(id){

fetch("/asl_platform/ajax.php",{
method:"POST",
headers:{"Content-Type":"application/x-www-form-urlencoded"},
body:"action=get_message&id="+id
})
.then(res=>res.json())
.then(data=>{

document.getElementById("modalName").innerText=data.name;
document.getElementById("modalEmail").innerText=data.email;
document.getElementById("modalMessage").innerText=data.message;

currentMessageEmail=data.email;

document.getElementById("messageModal").style.display="block";

});

}

function sendReply(){

let reply = document.getElementById("replyText").value;

fetch("/asl_platform/ajax.php",{
method:"POST",
headers:{
"Content-Type":"application/x-www-form-urlencoded"
},
body:"action=send_reply&email="+encodeURIComponent(currentMessageEmail)+"&message="+encodeURIComponent(reply)
})
.then(res=>res.text())
.then(()=>{

alert("Reply sent successfully");
document.getElementById("replyText").value="";

});

}

function closeInboxModal(){

document.getElementById("messageModal").style.display="none";

}



function deleteMessage(id){

if(!confirm("Delete this message?")) return;

fetch("/asl_platform/ajax.php",{
method:"POST",
headers:{"Content-Type":"application/x-www-form-urlencoded"},
body:"action=delete_message&id="+id
})

.then(()=>{

location.reload();

});

}

document.getElementById("messageSearch").addEventListener("keyup",function(){

let filter=this.value.toLowerCase();

let rows=document.querySelectorAll("#messagesTable tr");

rows.forEach(row=>{

let text=row.innerText.toLowerCase();

row.style.display=text.includes(filter)?"":"none";

});

});


window.onclick = function(event){

let modal = document.getElementById("assetModal");

if(event.target === modal){
modal.style.display = "none";
}

}

function sendRequest(assetId,type){

let msg = document.getElementById("requestMessage").value;

fetch("/asl_platform/ajax.php",{
method:"POST",
headers:{
"Content-Type":"application/x-www-form-urlencoded"
},
body:"action=send_request&asset_id="+assetId+"&type="+type+"&message="+encodeURIComponent(msg)
})

.then(res=>res.text())
.then(data=>{
alert(data);
});

}

function viewRequest(id){

console.log("Opening request:", id);

fetch("/asl_platform/ajax.php",{
method:"POST",
headers:{
"Content-Type":"application/x-www-form-urlencoded"
},
body:"action=get_request&id="+id
})

.then(res=>res.json())

.then(data=>{

let html = `

<h2>Request Details</h2>

<p><strong>User:</strong> ${data.user_name}</p>
<p><strong>Email:</strong> ${data.user_email}</p>
<p><strong>Asset:</strong> ${data.asset_name}</p>
<p><strong>Type:</strong> ${data.request_type}</p>
<p><strong>Message:</strong> ${data.message}</p>
<p><strong>Status:</strong> ${data.status}</p>

`;

document.getElementById("requestDetails").innerHTML = html;

document.getElementById("requestModal").style.display = "block";

})

.catch(err=>{
console.error(err);
alert("Error loading request");
});

}

function closeRequestModal(){

document.getElementById("requestModal").style.display="none";

}

function filterRequests(){

let search = document.getElementById("searchRequests").value.toLowerCase();

let status = document.getElementById("statusFilter").value;

let type = document.getElementById("typeFilter").value;

let rows = document.querySelectorAll("#requestsTable tr");

rows.forEach((row,i)=>{

if(i==0) return;

let text = row.innerText.toLowerCase();

let show = true;

if(search && !text.includes(search)) show=false;

if(status && !text.includes(status)) show=false;

if(type && !text.includes(type)) show=false;

row.style.display = show ? "" : "none";

});

}

function closeRequestModal(){

document.getElementById("requestModal").style.display="none";

}

function openSidebar(){
document.getElementById("sidebarMenu").classList.add("active");
}

function closeSidebar(){
document.getElementById("sidebarMenu").classList.remove("active");
}