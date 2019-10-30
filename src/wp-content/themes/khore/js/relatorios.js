(function($) {
    $(document).ready(function(e) {
        $("#btnExportXLS").click(function () {
            $("#tblExport").btechco_excelexport({
                containerid: "tblExport",
                datatype: $datatype.Table,
                filename: 'relatorio-inscritos'
            });
        });
    });
})(jQuery);
