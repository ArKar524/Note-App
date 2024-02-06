var takeANote= document.getElementById('takeANote')
var title= document.getElementById('title')
var description= document.getElementById('description')
var closeBtn= document.getElementById('closeBtn')
var saveBtn = document.getElementById('saveBtn')

function inputBox() {
    takeANote.style.display= 'none'
    title.style.display= 'block'
    description.style.display= 'block'
    closeBtn.style.display= 'block'
    saveBtn.style.display= 'block'
}
function closeBox() {
    takeANote.style.display= 'block'
    title.style.display= 'none'
    description.style.display= 'none'
    closeBtn.style.display= 'none'
    saveBtn.style.display= 'none'
}


// edit
var editId = document.getElementById('id');
var editTitle = document.getElementById('editTitle');
var editDescription = document.getElementById('editDescription');
function edit(id, title, description,) {
    editId.value = id
    editTitle.value = title
    editDescription.value = description
    console.log(id)
    console.log(title)
    console.log(description)
}

// share
var shareId = document.getElementById('shareId');
var shareTitle = document.getElementById('shareTitle');
var shareDescription = document.getElementById('shareDescription');
var owner = document.getElementById('owner')
var ownerEmail = document.getElementById('ownerEmail')
function share(id, title, description, userName, userEmail) {
    shareId.value = id
    shareTitle.value = title
    shareDescription.value = description
    owner.innerHTML = userName + '(Owner)'
    ownerEmail.innerHTML = userEmail
    console.log(id)
    console.log(title)
    console.log(description)
    console.log(userName)
    console.log(userEmail)
}