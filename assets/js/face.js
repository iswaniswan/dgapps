Promise.all([
  faceapi.nets.faceRecognitionNet.loadFromUri(base_url + "assets/face"),
  faceapi.nets.faceLandmark68Net.loadFromUri(base_url + "assets/face"),
  faceapi.nets.ssdMobilenetv1.loadFromUri(base_url + "assets/face"),
]).then(start);

async function start() {
  const labeledFaceDescriptors = await fetch(base_url + "assets/testfoto.txt")
    .then((response) => response.text())
    .then((text) => {
      return JSON.parse(text);
    });

  localStorage.setItem("data", JSON.stringify(labeledFaceDescriptors));
  let f = await JSON.parse(localStorage.getItem("data"));
  let a = [];
  await f.map((item, index) => {
    let arr = [];
    arr[0] = new Float32Array(Object.keys(item._descriptors[0]).length);
    arr[1] = new Float32Array(Object.keys(item._descriptors[1]).length);
    for (let j = 0; j < 2; j++) {
      for (let i = 0; i < Object.keys(item._descriptors[j]).length; i++) {
        arr[j][i] = item._descriptors[j][i];
      }
    }
    a[index] = new faceapi.LabeledFaceDescriptors(item._label, arr);
  });
  const faceMatcher = new faceapi.FaceMatcher(a, 0.6);

  async function mulai(foto) {
    let image;
    image = await faceapi.fetchImage(foto);
    const displaySize = { width: image.width, height: image.height };
    const detections = await faceapi
      .detectAllFaces(image)
      .withFaceLandmarks()
      .withFaceDescriptors();
    const resizedDetections = faceapi.resizeResults(detections, displaySize);
    const results = resizedDetections.map((d) =>
      faceMatcher.findBestMatch(d.descriptor)
    );
    let data = [];
    let status;
    if (results[0]._label == "unknown") {
      status = false;
    } else {
      status = true;
    }
    data.push({ status: status, data: results[0]._label });
    var jsonString = JSON.stringify(data);
    document.write(jsonString);
  }
  mulai(foto);
}

function loadLabeledImages() {
  const labels = ["wahyu", "hendra"];
  return Promise.all(
    labels.map(async (label) => {
      const descriptions = [];
      for (let i = 1; i <= 2; i++) {
        const img = await faceapi.fetchImage(
          `http://localhost/face/foto/${label}-${i}.jpg`
        );
        const detections = await faceapi
          .detectSingleFace(img)
          .withFaceLandmarks()
          .withFaceDescriptor();
        descriptions.push(detections.descriptor);
      }

      return new faceapi.LabeledFaceDescriptors(label, descriptions);
    })
  );
}
