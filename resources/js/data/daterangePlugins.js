// vanilla js
/**
 * @typedef {Object} FlatpickrPluginRangePreset
 * @property {string} label
 * @property {number|Date|undefined} [start] Leave undefined to clear the date.
 * @property {number|Date} [end]
 * @property {boolean} [isDefaultSelected] Whether or not an option should be set as selected by default.
 */

 const PLUGIN_CLASSNAME = 'predefined-range';

 const BASE_CLASSNAME = 'flatpickr-predefined-ranges';
 const RANGES_CONTAINER_CLASSNAME = `${BASE_CLASSNAME}__ranges-container`;
 const BASE_OPTION_CLASSNAME = `${BASE_CLASSNAME}__ranges-container__option`;
 const SELECTED_OPTION_CLASSNAME = `${BASE_OPTION_CLASSNAME}--selected`;
 
 const MONTHS_AND_DATES_CONTAINER_CLASSNAME = `${BASE_CLASSNAME}__month-and-dates-container`;
 
 /**
  * Adds a list of classes to an element, IE11 compliant.
  * @param {HTMLElement} elem
  * @param {...string} classes
  */
 function addClassesToElem(elem, ...classes) {
   classes.forEach((className) => {
     elem.classList.add(className);
   });
 }
 
 /**
  * Removes a list of classes from an element, IE11 compliant.
  * @param {HTMLElement} elem
  * @param {...string} classes
  */
 function removeClassesFromElem(elem, ...classes) {
   classes.forEach((className) => {
     elem.classList.remove(className);
   });
 }
 
 /**
  * @param {FlatpickrPluginRangePreset[]} presets
  * @param {string[]} [extraRangesContainerClasses=[]] a list of extra classes for the ranges container
  * @param {string[]} [extraRangeOptionClasses=[]] a list of extra classes for each range option.
  * @param {string[]} [extraRangeOptionSelectedClasses=[]] a list of extra classes for each range option when selected.
  * @returns {flatpickr.Options.Plugin}
  */
 export default function predefinedRangesPlugin(presets, {
   extraRangesContainerClasses = [],
   extraRangeOptionClasses = [],
   extraRangeOptionSelectedClasses = [],
 } = {}) {
   if (!presets || !Array.isArray(presets)) {
     throw new TypeError(`Presets must be an array, not ${presets}`);
   }
 
   if (presets.length === 0) {
     throw new Error('Must provide at least one preset.');
   }
 
   return (fp) => {
     /**
      * The element that contains the range options.
      * @type {HTMLElement}
      */
     let rangesContainer;
     let chosenOptionElem = null;
 
     /**
      * Clear the currently selected options, if one has been chosen.
      */
     const clearSelection = () => {
       if (chosenOptionElem !== null) {
         removeClassesFromElem(chosenOptionElem, SELECTED_OPTION_CLASSNAME, ...extraRangeOptionSelectedClasses);
         chosenOptionElem = null;
       }
     };
 
     /**
      * Set the currently selected option.
      * @param {HTMLElement} elem
      */
     const setSelection = (elem) => {
       chosenOptionElem = elem;
       addClassesToElem(chosenOptionElem, SELECTED_OPTION_CLASSNAME, ...extraRangeOptionSelectedClasses);
     };
 
     return {
       onReady() {
         rangesContainer = fp._createElement('div', RANGES_CONTAINER_CLASSNAME);
         addClassesToElem(rangesContainer, ...extraRangesContainerClasses);
         rangesContainer.tabIndex = -1;
 
         presets.forEach(({
           label, start, end, isDefaultSelected,
         }) => {
           const optionElem = fp._createElement('div', BASE_OPTION_CLASSNAME, label);
           addClassesToElem(optionElem, ...extraRangeOptionClasses);
           optionElem.tabIndex = 0;
 
           if (isDefaultSelected) {
             setSelection(optionElem);
           }
           optionElem.addEventListener('click', (event) => {
             // set the date range and trigger the change to handlers
             if (!start) {
               fp.clear();
             } else {
               fp.setDate([start, end], true);
             }
             fp.close();
             setSelection(event.target);
           });
           rangesContainer.appendChild(optionElem);
         });
 
         // Restructure the calendar to make it easier to style
         // placing the months and inner container into
         // one new sibling container to the ranges container
         // all wrapped in a base container
         // NOTE: if the calendarContainer has more than 1 child and it is not
         // vertically stacked then it will not calculate the correct position
         // for "above" positioning
         const monthAndDatesContainer = fp._createElement('div', MONTHS_AND_DATES_CONTAINER_CLASSNAME);
         monthAndDatesContainer.appendChild(fp.monthNav);
         monthAndDatesContainer.appendChild(fp.innerContainer);
         const baseContainer = fp._createElement('div', BASE_CLASSNAME);
 
         baseContainer.appendChild(monthAndDatesContainer);
         baseContainer.appendChild(rangesContainer);
         fp.calendarContainer.appendChild(baseContainer);
         addClassesToElem(fp.calendarContainer, PLUGIN_CLASSNAME);
       },
       onChange() {
         // Clear the selection when the user
         // chooses date on the calendar instead of a preset range
         clearSelection();
       },
     };
   };
 }
 