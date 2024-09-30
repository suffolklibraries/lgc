import axios from "axios";

document.addEventListener('alpine:init', () => {
    Alpine.data('eventLocationForm', () => ({
        addresses: [],
        loading: true,

        init() {
            axios.get('/dashboard/addresses/api/index')
                .then((response) => {
                    this.addresses = response.data
                    this.loading = false
                })
        },

        apply(address) {
            this.$root.querySelector("#address_line_1").value = address.address_line_1
            this.$root.querySelector("#address_line_2").value = address.address_line_2
            this.$root.querySelector("#town").value = address.town
            this.$root.querySelector("#postcode").value = address.postcode
            this.$root.querySelector("#lat").value = address.lat
            this.$root.querySelector("#lng").value = address.lng

            if(address.building_name) {
                this.$root.querySelector("#building_name").value = address.building_name
            } else {
                this.$root.querySelector("#building_name").value = null
            }

            let customEvent = new CustomEvent("updateTipTapContent", {
                detail: {
                    value: address.directions,
                    field: "directions"
                },
                bubbles: true
            });

            this.$el.dispatchEvent(customEvent);
        },
    })
)})
