
import 'select2';
import "select2/dist/css/select2.min.css";

export default class Select2 {
  constructor() {
    this.Select2Dropdown = ".select2";
    this.Select2Dropdown = "#ff_1, .page-template-project-listing";
    this.bindEvents();
  }

  bindEvents = () => {
    if (document.querySelectorAll(this.Select2Dropdown).length) {
      this.Select2DropdownInit();
    }
  };

  Select2DropdownInit = () => {

    //Select2 ini with animation
      $('.select2').select2({
      })
      $('#type-of-service-8').select2({
        dropdownParent: $('#type-of-service-8').parent(),
        dropdownPosition: 'below',
        placeholder: 'Select an option'
      });
  };
}
