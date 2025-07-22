document.addEventListener('DOMContentLoaded', async () => {
  await customElements.whenDefined('gmpx-store-locator');
  const locator = document.querySelector('gmpx-store-locator');
  locator.configureFromQuickBuilder(CONFIGURATION);
});const CONFIGURATION = {
        "locations": [
          {"title":"Pinetree Community Centre","address1":"1260 Pinetree Wy","address2":"Coquitlam, BC V3B 7X3, Canada","coords":{"lat":49.289541861747765,"lng":-122.79151422023774},"placeId":"ChIJ2wWQji9_hlQRclVa4orrnbw"},
          {"title":"Lochdale Hall","address1":"Sperling Ave \u0026 Hastings St","address2":"Burnaby, BC V5B 1S3, Canada","coords":{"lat":49.279864465342584,"lng":-122.96431105515596},"placeId":"EjdTcGVybGluZyBBdmUgJiBIYXN0aW5ncyBTdCwgQnVybmFieSwgQkMgVjVCIDFTMywgQ2FuYWRhImYiZAoUChIJW3r-RlZ3hlQR6WzJnhGaqCsSFAoSCVt6_kZWd4ZUEelsyZ4RmqgrGhQKEgm1iUuTDHeGVBFsEuWEgD-c8RoUChIJB6pRcf15hlQRyefMnBuf1l4iCg0ell8dFSQUtbY"},
          {"title":"Killarney Community Centre","address1":"6250 Killarney St","address2":"Vancouver, BC V5S 2X7, Canada","coords":{"lat":49.22712527641595,"lng":-123.04412717644348},"placeId":"EjA2MjUwIEtpbGxhcm5leSBTdCwgVmFuY291dmVyLCBCQyBWNVMgMlg3LCBDYW5hZGEiMRIvChQKEglxhuv8gnaGVBGjvxBCR1g4exDqMCoUChIJPeoGQ5t2hlQR5KYn9hl2hHY"},
          {"title":"Britannia Community Centre","address1":"1661 Napier St","address2":"Vancouver, BC V5L 2K6, Canada","coords":{"lat":49.275103698965275,"lng":-123.07037186441804},"placeId":"ChIJl2fXHF1xhlQRWF7CrX2POQQ"},
          {"title":"Douglas Park Community Centre","address1":"801 W 22nd Ave","address2":"Vancouver, BC V5Z 0E1, Canada","coords":{"lat":49.25218790913045,"lng":-123.12249513558197},"placeId":"ChIJx6Bi6OxzhlQRi6b2PkG0d-g"}
        ],
        "mapOptions": {"center":{"lat":38.0,"lng":-100.0},"fullscreenControl":true,"mapTypeControl":false,"streetViewControl":false,"zoom":4,"zoomControl":true,"maxZoom":17,"mapId":""},
        "mapsApiKey": "AIzaSyAzFpOyl19QenOe4dOho44PQkTTiZYxoPE",
        "capabilities": {"input":true,"autocomplete":true,"directions":false,"distanceMatrix":true,"details":false,"actions":false}
      };