ccmValidateBlockForm = function() {
   if ($('#formID').val() == '' || $('#formID').val() == 0) {
      ccm_addError(ccm_t('form-required'));
   }
   return false;
};