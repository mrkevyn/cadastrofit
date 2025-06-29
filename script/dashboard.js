$(document).ready(function () {

    let currentSortedColumn = 0; // Índice da coluna Nome
    let currentOrderAsc = true;  // Começa ordenado crescente

    ordenarTabela(currentSortedColumn, currentOrderAsc); // Executa a ordenação inicial na coluna Nome

    $('th').click(function () {
        const columnIndex = $(this).index();

        if (currentSortedColumn === columnIndex) {
            currentOrderAsc = !currentOrderAsc; // Inverte a ordem se clicar na mesma coluna
        } else {
            currentSortedColumn = columnIndex;
            currentOrderAsc = true; // Nova coluna começa crescente
        }

        ordenarTabela(currentSortedColumn, currentOrderAsc);
    });

    function ordenarTabela(columnIndex, asc) {
        const table = $('table');
        const rows = table.find('tr:gt(0)').toArray().sort(comparer(columnIndex));

        if (!asc) {
            rows.reverse();
        }

        rows.forEach(row => table.append(row));

        // Limpa os indicadores anteriores
        $('th .sort-indicator i').removeClass('fa-sort-up fa-sort-down');

        // Adiciona o indicador na coluna atual
        const th = $('th').eq(columnIndex);
        const icon = th.find('.sort-indicator i');
        if (asc) {
            icon.addClass('fa-sort-up');
        } else {
            icon.addClass('fa-sort-down');
        }
    }

    function comparer(index) {
        return function (a, b) {
            const valA = getCellValue(a, index);
            const valB = getCellValue(b, index);
            return $.isNumeric(valA) && $.isNumeric(valB)
                ? valA - valB
                : valA.toString().localeCompare(valB);
        };
    }

    function getCellValue(row, index) {
        return $(row).children('td').eq(index).text();
    }

    $('#search').on('input', function () {
        filterTable($(this).val());
    });

    function filterTable(query) {
        const table = $('table');
        table.find('tr:gt(0)').each(function () {
            const row = $(this);
            const name = row.find('td:eq(0)').text().toLowerCase();
            if (name.includes(query.toLowerCase())) {
                row.show();
            } else {
                row.hide();
            }
        });
    }
});

    document.addEventListener('DOMContentLoaded', function () {
        // Get all table rows
        var rows = document.querySelectorAll('table tr');
    
        // Add click event listeners to each row
        rows.forEach(function (row) {
            row.addEventListener('click', function () {
                // Toggle a class on the clicked row
                this.classList.toggle('clicked');
            });
        });
    });