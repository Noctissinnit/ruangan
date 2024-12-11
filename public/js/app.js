window.alert = (value) => {
    if (typeof value === "string") {
        Swal.fire({
            title: "Information",
            text: value,
            icon: "info"
        });
    } else {
        Swal.fire(value);
    }
}

window.error = (value) => {
    alert({
        title: 'Error',
        text: value,
        icon: 'error'
    });
}

window.success = (value) => {
    alert({
        title: 'Success',
        text: value,
        icon: 'success'
    });
}