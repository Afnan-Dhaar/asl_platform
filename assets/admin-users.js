
function aslOpenModal(id){
document.getElementById(id).style.display='block';
}

function aslCloseModal(id){
document.getElementById(id).style.display='none';
}

function aslEditUser(id,name,email,role,status){
document.getElementById('asl_edit_id').value=id;
document.getElementById('asl_edit_name').value=name;
document.getElementById('asl_edit_email').value=email;
document.getElementById('asl_edit_role').value=role;
document.getElementById('asl_edit_status').value=status;
aslOpenModal('aslEditModal');
}

function aslToggleAll(source){
    let checkboxes = document.querySelectorAll('.asl_user_checkbox');
    checkboxes.forEach(function(checkbox){
        checkbox.checked = source.checked;
    });
}

setTimeout(()=>{
let toast=document.querySelector('.asl_admin_toast');
if(toast) toast.style.display='none';
},3000);

document.addEventListener('change', function(e){
    if(e.target.classList.contains('asl_user_checkbox')){
        let all = document.querySelectorAll('.asl_user_checkbox');
        let checked = document.querySelectorAll('.asl_user_checkbox:checked');
        let selectAll = document.getElementById('asl_select_all');

        if(all.length === checked.length){
            selectAll.checked = true;
        } else {
            selectAll.checked = false;
        }
    }
});