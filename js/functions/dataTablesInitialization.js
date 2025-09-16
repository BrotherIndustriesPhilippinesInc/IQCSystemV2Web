export default function dataTablesInitialization(selector, params = {responsive: true,}) {
    const defaultOptions = {
        responsive: true,
        fixedHeader: true,
    };

    $(selector).DataTable({
        ...defaultOptions,
        ...params
    });

    return $(selector).DataTable();
}
