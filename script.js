const loginLink = document.getElementById("loginLink");
const registerLink = document.getElementById("registerLink");
const loginForm = document.getElementById("loginform");
const registerForm = document.getElementById("registerform");

registerLink.addEventListener('click', function(){
    loginForm.style.display="none";
    registerForm.style.display="block";
})

loginLink.addEventListener('click', function(){
    registerForm.style.display="none";
    loginForm.style.display="block";
})
document.getElementById('forgotPassword').addEventListener('click', function(event) {
    event.preventDefault();
    alert('Please contact the admin to reset your password.');
});