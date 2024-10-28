define(['jquery'], function ($) {

    "use strict";
    return function (config) {
        $(function () {
            var offers = config.offers;
            var currentOfferIndex = 0;

            // Function to render an offer
            function renderOffer(index) {
                var offer = offers[index];
                console.log(offer);
                $('#offer-presentation img').attr("src", "media/offer/images/" + offer.image);
                $('#offer-plus-link').attr("href", "offers/view/offer/id/" + offer.offer_id)
            }

            // Initial rendering of the first offer
            // renderOffer(currentOfferIndex);

            // Function to handle next offer click
            $('#next-offer').on('click', function () {
                if (currentOfferIndex < offers.length - 1) {
                    currentOfferIndex++;
                } else {
                    currentOfferIndex = 0; // Loop back to the first offer
                }
                renderOffer(currentOfferIndex);
            });

            // Function to handle previous offer click
            $('#prev-offer').on('click', function () {
                if (currentOfferIndex > 0) {
                    currentOfferIndex--;
                } else {
                    currentOfferIndex = offers.length - 1; // Loop back to the last offer
                }
                renderOffer(currentOfferIndex);
            });
        })
    }
});

