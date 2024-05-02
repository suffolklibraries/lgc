import { loadGoogleMapsScript } from './events'
import { Editor } from '@tiptap/core'
import StarterKit from '@tiptap/starter-kit'
import { initTipTap } from './tiptap'

function EventSubmissionForm () {
    const forms = document.querySelectorAll('.event-submission-form')

    if(!forms.length) {
        return
    }

    const settings = window._maps;
    window.initAutocomplete = initAutocomplete;

    loadGoogleMapsScript(settings.api, "initAutocomplete")
    initTipTapFields()

    function initAutocomplete() {

        forms.forEach((form) => {
            const autocompleteElements = form.querySelectorAll('input[name=location_autocomplete]')

            autocompleteElements.forEach((el) => {
                let autocomplete = new google.maps.places.Autocomplete(el, {
                    componentRestrictions: {
                      country: "gb",
                    },
                })

                autocomplete.addListener("place_changed", function () {

                    const place = autocomplete.getPlace()

                    if (!place.geometry) {
                        return;
                    }

                    locate(
                        place,
                        form
                    );
                  });
            })
        })

    }

    function initTipTapFields() {
        forms.forEach((form) => {
            let tiptapElements = form.querySelectorAll('.wysiwyg')

            tiptapElements.forEach((el) => {
                let editor = initTipTap(
                    el,
                    '<p>Hello, World!,</p><ul><li><p>TEST</p></li></ul><a href="https://canyon.com">a cool link</a>'
                )
            })
        })
    }

    function locate(place, form) {
        const latEl = form.querySelector('[name="lat"]')
        const lngEl = form.querySelector('[name="lng"]')
        if (!latEl || !lngEl) return

        latEl.value = place.geometry.location.lat()
        lngEl.value = place.geometry.location.lng()

        const addressLine1El = form.querySelector('[name="address_line_1"]')
        const addressLine2El = form.querySelector('[name="address_line_2"]')
        const townEl = form.querySelector('[name="town"]')
        const postcodeEl = form.querySelector('[name="postcode"]')

        addressLine1El.value = null
        addressLine2El.value = null
        townEl.value = null
        postcodeEl.value = null

        let addressLine1, addressLine2, town, postcode

        place.address_components.forEach(component => {
            if (component.types.includes('street_number')) {
                addressLine1 = component.long_name;
            } else if (component.types.includes('route')) {
                if(!addressLine1) {
                    addressLine1 = component.long_name
                } else {
                    addressLine1 += ' ' + component.long_name
                }
            } else if (component.types.includes('locality')) {
                addressLine2 = component.long_name
            } else if(component.types.includes('sublocality')) {
                addressLine2 = component.long_name
            } else if (component.types.includes('postal_town')) {
                town = component.long_name
            } else if (component.types.includes('postal_code')) {
                postcode = component.long_name
            }
        });

        if(!addressLine1) {
            addressLine1 = place.formatted_address.split(',')[0].trim()
        }

        console.log(addressLine1)


        if(addressLine1) {
            addressLine1El.value = addressLine1
        }

        if(addressLine2) {
            addressLine2El.value = addressLine2
        }

        if(town) {
            townEl.value = town
        }

        if(postcode) {
            postcodeEl.value = postcode
        }
    }
}


export default EventSubmissionForm;
