/** 
 * @param {FormData} form
 * @param {string[]} fields
*/
function validateEmptyForm(form, fields) {
    for (const key in fields) {
        if (form.has(key) && form.get(key).length === 0) {
            error(`Kolom "${fields[key]}" harus di-isi!`);
            return false;
        } else {
            console.warn(`Missing key "${key}" on form empty validation. Ignoring...`);
        }
    }
    return true;
}