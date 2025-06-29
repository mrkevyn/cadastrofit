function toggleListaDatas(listaId, iconeId) {
    var listaDatas = document.getElementById(listaId);
    var setaIcon = document.getElementById(iconeId);

    if (listaDatas.style.display === 'none') {
        listaDatas.style.display = 'block';
        setaIcon.classList.remove('fa-chevron-down');
        setaIcon.classList.add('fa-chevron-up');
    } else {
        listaDatas.style.display = 'none';
        setaIcon.classList.remove('fa-chevron-up');
        setaIcon.classList.add('fa-chevron-down');
    }
}
