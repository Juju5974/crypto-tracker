const formCurrency = document.getElementById('form_currency');
const formQuantity = document.getElementById('form_quantity');
const formAmount = document.getElementById('form_amount');

formCurrency.addEventListener('change', changeCurrencyFunction);
formQuantity.addEventListener('change', amountFunction);
formAmount.addEventListener('change', quantityFunction);

let unitPrice = 0;

function changeCurrencyFunction(event) {
    unitPrice = event.target.options[event.target.selectedIndex].dataset.amount;
    if (formCurrency.value !== '' && formQuantity.value !== '') {
        formAmount.value = (unitPrice * formQuantity.value).toFixed(2);
    }
};

function amountFunction() {
    if (formCurrency.value !== '') {
        if (unitPrice !== 0) {
            formAmount.value = (unitPrice * formQuantity.value).toFixed(2);
            console.log(unitPrice)
        } else if (formCurrency.value !== '') {
            unitPrice = formCurrency.options[formCurrency.selectedIndex].dataset.amount;
        }
    }
};

function quantityFunction() {
    if (formCurrency.value !== '' && formAmount.value !== '') {
        quantity = (formAmount.value / unitPrice).toFixed(3);
        if (quantity === 'Infinity') {
            formQuantity.value = 0
            formAmount.value = 0
        } else {
            formQuantity.value = quantity;
        }
    }
};