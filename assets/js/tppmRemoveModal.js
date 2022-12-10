window.addEventListener('load', function() {
    function removeTPPMModal() {
        const tppmModalDOM = document.querySelector('#js-tppm-modal');
        const tppmModalOverlayDOM = document.querySelector('#js-tppm-modal-overlay');
        if (tppmModalDOM === null || tppmModalOverlayDOM === null) {
            return;
        }
        
        tppmModalDOM.remove();
        tppmModalOverlayDOM.remove();
    }
    removeTPPMModal();
});