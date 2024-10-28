require(
    [
        'Magento_Ui/js/lib/validation/validator',
        'jquery',
        'mage/translate'
    ], function(validator, $){
        validator.addRule(
            'validate-end-date',
            function (value) {
                const start_date = parseDate($('input[name="start_date"]').val());
                const end_date = parseDate(value);
                return !(!start_date || !end_date || start_date > end_date);
            },
            $.mage.__('La date de fin doit être supérieure ou égale à la date de début')
        );
        function parseDate(dateStr) {
            const [day, month, year] = dateStr.split('/');
            return new Date(`${year}-${month}-${day}`);
        }
    });
