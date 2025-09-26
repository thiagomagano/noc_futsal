import "./bootstrap";

import Alpine from "alpinejs";

window.Alpine = Alpine;

Alpine.start();

window.formatTelefone = function (value) {
    value = value.replace(/\D/g, "");
    if (value.length >= 11) {
        return value.replace(/(\d{2})(\d{5})(\d{4})/, "($1) $2-$3");
    } else if (value.length >= 7) {
        return value.replace(/(\d{2})(\d{4})(\d+)/, "($1) $2-$3");
    } else if (value.length >= 3) {
        return value.replace(/(\d{2})(\d+)/, "($1) $2");
    }
    return value;
};
