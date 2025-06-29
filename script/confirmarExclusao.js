function confirmarExclusao(event, alunoId) {
    var resposta = confirm("Tem certeza que deseja excluir este aluno?");
    if (!resposta) {
        // Substitua o valor de 'alunoId' diretamente aqui para que o JS possa usar o ID do aluno
        window.location.replace("../public/detalhes_aluno.php?id=" + alunoId);
        return false;
    }
    return true;
}

// Impede a propagação do evento de clique da tr para os elementos filhos
document.querySelectorAll('.delete-container').forEach(function(container) {
    container.addEventListener('click', function(event) {
        event.stopPropagation();
    });
});
