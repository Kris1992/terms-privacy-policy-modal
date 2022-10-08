window.addEventListener('load', function() {

    function anchorify(text) {
        const exp = /(\b(https?|ftp|file):\/\/[-A-Z0-9+&@#\/%?=~_|!:,.;]*[-A-Z0-9+&@#\/%=~_|])/ig;
        const text1=text.replace(exp, "<a href='$1'>$1</a>");
        const exp2 =/(^|[^\/])(www\.[\S]+(\b|$))/gim;
        return text1.replace(exp2, '$1<a target="_blank" href="http://$2">$2</a>');
    }

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
        if (checkboxesDOM.length <= 0) {
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

    const tppmLabelsDOM = document.querySelectorAll('.js-tppm-checkbox-label-text');
    if (tppmLabelsDOM.length > 0) {
        tppmLabelsDOM.forEach(function(label) {
            label.innerHTML = anchorify(label.textContent);
        });
    }
    
    tppmModalOverlayDOM.classList.add('active');
    tppmModalDOM.style.display = 'grid';  

    const tppmFormDOM = document.querySelector('#js-tppm-submit-form');
    if (tppmFormDOM === null) {
        return;
    }

    const checkboxesDOM = document.querySelectorAll('.js-tppm-modal-checkbox');
    if (checkboxesDOM.length <= 0) {
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
        const inputsDOM = document.querySelectorAll('.js-tppm-modal-input');
        if (inputsDOM.length <= 0) {
            return;
        }
        
        let data = {};
        inputsDOM.forEach(function(input) {
            if (input.type === 'checkbox') {
                Object.assign(data, {[input.name]: input.checked});
            } else {
                Object.assign(data, {[input.name]: input.value});
            }
        }); 

        jQuery.ajax({
            type: 'POST',
            url: urlHandler.ajaxUrl,
            data: {
                action: 'ttpmSetUserTerms',
                acceptedModalId: acceptedModalId,
                nonce: data.nonce || '',
                data: data
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