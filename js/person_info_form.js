(function (Drupal) {
  Drupal.behaviors.personInfoFormEnhancer = {
    attach: function (context, settings) {
      const colorField = document.querySelector('select[multiple]');
      if (colorField && !colorField.classList.contains('choices-initialized')) {
        const choices = new Choices(colorField, {
          removeItemButton: true,
          placeholderValue: 'Select favorite color(s)',
          itemSelectText: '',
          shouldSort: false,
          duplicateItemsAllowed: false,
          renderSelectedChoices: 'always',
          classNames: {
            containerInner: 'choices__inner',
            listDropdown: 'choices__list--dropdown',
            itemChoice: 'choices__item--choice',
            selectedState: 'is-selected',
          },
          callbackOnCreateTemplates: function (template) {
            return {
              choice: (classNames, data) => {
                return template(`
                  <div class="${classNames.item} ${classNames.itemChoice} ${data.disabled ? classNames.itemDisabled : ''} ${data.selected ? classNames.selectedState : ''}"
                       data-select-text="" data-choice data-id="${data.id}" data-value="${data.value}" ${data.disabled ? 'data-choice-disabled aria-disabled="true"' : 'data-choice-selectable'}>
                    <label>
                      <input type="checkbox" class="custom-choice-checkbox"
                             ${data.selected ? 'checked' : ''} data-choice-checkbox
                             data-id="${data.id}" data-value="${data.value}" />
                      <span>${data.label}</span>
                    </label>
                  </div>
                `);
              }
            };
          }
        });

        document.addEventListener('change', function (e) {
          if (e.target.matches('input[data-choice-checkbox]')) {
            const id = e.target.getAttribute('data-id');
            const value = e.target.getAttribute('data-value');
            const currentItems = choices.getValue(true);

            if (e.target.checked && !currentItems.includes(value)) {
              choices.setChoiceByValue(value);
            } else if (!e.target.checked && currentItems.includes(value)) {
              choices.removeActiveItemsByValue(value);
            }
          }
        });

        colorField.classList.add('choices-initialized');
      }
    }
  };
})(Drupal);
