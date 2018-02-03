window.onload = getRegisters()
const username = document.form1.username
const button = document.getElementById('button')
const password = document.form1.password
let updateId = null
let users = []
const updateButton = document.querySelector('.update')
const table = document.getElementById('usersTable')
document.form1.addEventListener('submit', e => {
  e.preventDefault()
  sendRequest()
})
async function getRegisters() {
  const res = await fetch('http://localhost/restful-api/users/getAll')
  const data = await res.json()
  if (data.status === 200) {
    users = data.users
    rendered()
  } else {
    alert(data.message)
  }
}
function rendered() {
  table.innerHTML = ''
  table.style.display = 'table'
  if (users.length) {
    table.innerHTML = '<tr><th>Username</th><th>Password</th><th>Actions</th></tr>'
    users.forEach(e => addRow(e))
  } else {
    table.style.display = 'none'
  }
}
function addRow(data) {
  const newRow = table.insertRow(1)
  newRow.insertCell(0).innerHTML = data.username
  newRow.insertCell(1).innerHTML = data.password
  newRow.insertCell(2).innerHTML = `<a onCLick="myDelete(${data.id})">Eliminar</a> | <a onCLick="update(${data.id})">Actualizar</a>`
}
function update(id) {
  updateButton.classList.add('active')
  button.value = 'Actualizar'
  updateId = id
  users.forEach(e => {
    if (e.id == id) {
      password.value = e.password
      username.value = e.username
    }
  })

}
function cancelUpdate() {
  updateButton.classList.remove('active')
  button.value = 'Registrar'
  document.form1.reset()
}
async function myDelete(id) {
  const res = await fetch(`http://localhost/restful-api/users/deleteById/${id}`, { method: 'DELETE' })
  const data = await res.json()
  if (data.status == 200) {
    users = users.filter(e => e.id != id)
    swal('Excelente.', data.message, 'success')
  } else {
    swal('Ooops.', data.message, 'error')    
  }
  rendered()
}
async function sendRequest() {
  let data
  if (button.value === 'Actualizar') {
    const res = await fetch('http://localhost/restful-api/users/update', {
      method: 'PUT',
      body: {
        id: updateId,
        username: username.value,
        password: password.value
      }
    })
    console.log(res)
    data = await res.json()
    button.value = 'Registrar'
  } else {
    const body = new FormData(document.form1)
    const res = await fetch('http://localhost/restful-api/users/create', {
      method: 'POST',
      body
    })
    data = await res.json()
  }
  if (data.status === 200) {
    swal('Excelente', data.message, 'success')
    getRegisters()
  } else swal('Ooops...', data.message, 'error')
}