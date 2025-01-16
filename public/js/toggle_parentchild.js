function toggleChildTable(button) {
    const parentRow = button.closest(".parent-row");
    const childTable = parentRow.nextElementSibling;

    if (childTable.style.display === "none") {
        childTable.style.display = "table-row";
        button.textContent = "^";
    } else {
        childTable.style.display = "none";
        button.textContent = ">";
    }
}