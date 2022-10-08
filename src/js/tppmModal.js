window.addEventListener('load', function() {

    function setTPPMErrors(message) {
        const tppmModalErrorsWrapperDOM = document.querySelector('#js-tppm-errors-wrapper');
        if (tppmModalErrorsWrapperDOM === null) {
            return;
        }
        tppmModalErrorsWrapperDOM.textContent = message;
    }

    function cleanTPPMErrors() {
        setTPPMErrors('');
    }

    function hideTPPMModal() {
        const tppmModalDOM = document.querySelector('#js-tppm-modal');
        const tppmModalOverlayDOM = document.querySelector('#js-tppm-modal-overlay');
        if (tppmModalDOM === null || tppmModalOverlayDOM === null) {
            return;
        }
        tppmModalDOM.remove();
        tppmModalOverlayDOM.remove();
    }

    function checkTPPMTermsCheckboxes() {
        const checkboxesDOM = document.querySelectorAll('.js-tppm-modal-checkbox');
        if (checkboxesDOM === null) {
            return false;
        }
        let result = true;
        checkboxesDOM.forEach(function(checkbox) {
            if (!checkbox.checked) {
                result = false;
            }
        }); 

        return result;
    }

    const tppmModalDOM = document.querySelector('#js-tppm-modal');
    const tppmModalOverlayDOM = document.querySelector('#js-tppm-modal-overlay');
    if (tppmModalDOM === null || tppmModalOverlayDOM === null) {
        return;
    }
    tppmModalOverlayDOM.classList.add('active');
    tppmModalDOM.style.display = 'grid';  

    const tppmFormDOM = document.querySelector('#js-tppm-submit-form');
    if (tppmFormDOM === null) {
        return;
    }

    const checkboxesDOM = document.querySelectorAll('.js-tppm-modal-checkbox');
    if (checkboxesDOM !== null) {
        checkboxesDOM.forEach(function(checkbox) {
            checkbox.addEventListener('change', function() {
                cleanTPPMErrors();
            });
        });
    }

    tppmFormDOM.addEventListener('submit', function(event) {
        event.preventDefault();
        cleanTPPMErrors();
        if (!checkTPPMTermsCheckboxes()) {
            setTPPMErrors('Zaznaczenie zgód jest wymagane');
            return;
        }
        const tppmModalDOM = document.querySelector('#js-tppm-modal');
        if (tppmModalDOM === null) {
            return;
        }
        const acceptedModalId = tppmModalDOM.dataset.tppmModalId || 0;

        jQuery.ajax({
            type: 'POST',
            url: urlHandler.ajaxUrl,
            data: {
                action: 'ttpmSetUserTerms',
                acceptedModalId: acceptedModalId
            },
            success: function(response) {
                if (parseInt(response, 10) === 0 
                    || typeof response.data === 'undefined' 
                    || typeof response.data.message === 'undefined'
                ) {
                    setTPPMErrors('Zapis danych zakończył się niepowodzeniem');
                    return;
                }
                if (response.data.message === 'OK') {
                    hideTPPMModal();
                }
            },
            error: function(xhr) {
                const response = JSON.parse(xhr.responseText);
                if (typeof response === 'undefined' && typeof response.data === 'undefined') {
                    setTPPMErrors('Zapis danych zakończył się niepowodzeniem');
                    return;
                } 
                setTPPMErrors(response.data.message || 'Zapis danych zakończył się niepowodzeniem');
            }
        });

    });

// js-tppm-checkbox-label
// js-tppm-modal-description

});