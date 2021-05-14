export const iframeMessageSender = {
  methods: {
      resizeIframe() {
          const height = document.querySelector('#rendered-resource').offsetHeight;
          window.parent.postMessage(JSON.stringify({subject: 'lti.frameResize', height: height + 100}), '*');
          this.scrollToTop();
      },
      scrollToTop() {
          window.parent.postMessage(JSON.stringify({subject: 'lti.scrollToTop'}), '*');
      },
      removeIframeBorder() {
          window.parent.postMessage(JSON.stringify({subject: 'lti.removeBorder'}), '*');
      }
  }
};
