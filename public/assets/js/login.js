function switchForm(target) {
    event.preventDefault();
    document.getElementById('loginForm').classList.add('hidden');
    document.getElementById('registerForm').classList.add('hidden');
    document.getElementById('resetForm').classList.add('hidden');
    document.getElementById(target + 'Form').classList.remove('hidden');
}