/******/ (function() { // webpackBootstrap
var __webpack_exports__ = {};
/*!**********************!*\
  !*** ./src/index.js ***!
  \**********************/
// The code block below is only for enable/disable wp_editor in a hacky way
const maintenancePageCheck = document.getElementById('toggle-maintenance-page');
const settingFields = document.querySelectorAll('.maintenance-setting');
const settingFileInput = document.querySelector('.maintenance-setting-file');
const wpEditorWrapper = document.getElementById('wp-mp_maintenance_description-wrap');
// Add the disable class for the first load
if (maintenancePageCheck.checked) {
  wpEditorWrapper.classList.remove('disable-non-form-element');
} else {
  wpEditorWrapper.classList.add('disable-non-form-element');
}
maintenancePageCheck.addEventListener('click', event => {
  if (!event.target.checked) {
    settingFields.forEach(element => {
      element.readOnly = true;
      element.classList.add('opacity-50');
    });
    settingFileInput.disabled = true;
    wpEditorWrapper.classList.add('disable-non-form-element');
  } else {
    settingFields.forEach(element => {
      element.readOnly = false;
      element.classList.remove('opacity-50');
    });
    settingFileInput.disabled = false;
    wpEditorWrapper.classList.remove('disable-non-form-element');
  }
});
/******/ })()
;
//# sourceMappingURL=index.js.map