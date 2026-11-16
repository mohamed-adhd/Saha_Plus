// ================== CONFIG ==================
const MAX_SECONDS = 180;   // 3 minutes maximum (the user can stop at any time before)
const DETECT_EVERY_MS = 500;
const MODEL_URL = "https://cdn.jsdelivr.net/npm/@vladmandic/face-api/model";

const EMOJI = {
  neutral: "😐", happy: "😄", sad: "😢", angry: "😠",
  fearful: "😨", disgusted: "🤢", surprised: "😮",
};

// ================== ELEMENTS ==================
const $ = (id) => document.getElementById(id);
const video = $("video"), blurCanvas = $("blurCanvas"), voiceOverlay = $("voiceOverlay");
const startBtn = $("startBtn"), stopBtn = $("stopBtn");
const statusEl = $("status"), timerEl = $("timer"), bar = $("bar");

// ================== STATE ==================
let camStream = null, recorder = null, chunks = [];
let samples = [];            // one entry per detection (expression probabilities)
let totalTicks = 0;          // number of detection attempts
let startTime = 0, tickTimer = null, detectTimer = null, blurRaf = null;
let busy = false, recordedBlob = null, recordedIsAudio = false;

// ================== LOAD MODELS ==================
(async function init() {
  try {
    await faceapi.nets.tinyFaceDetector.loadFromUri(MODEL_URL);
    await faceapi.nets.faceExpressionNet.loadFromUri(MODEL_URL);
    statusEl.textContent = "Ready. Choose a mode and press Start.";
    startBtn.disabled = false;
  } catch (e) {
    statusEl.textContent = "Could not load AI models (check your internet connection).";
    console.error(e);
  }
})();

// ================== HELPERS ==================
const getMode = () => document.querySelector('input[name="mode"]:checked').value;
const fmt = (s) => String(Math.floor(s / 60)).padStart(2, "0") + ":" + String(s % 60).padStart(2, "0");

function pickMime(audioOnly) {
  const list = audioOnly
    ? ["audio/webm;codecs=opus", "audio/webm", "audio/mp4"]
    : ["video/webm;codecs=vp9,opus", "video/webm;codecs=vp8,opus", "video/webm", "video/mp4"];
  return list.find((t) => window.MediaRecorder && MediaRecorder.isTypeSupported(t)) || "";
}

// ================== START ==================
startBtn.addEventListener("click", async () => {
  const mode = getMode();
  try {
    camStream = await navigator.mediaDevices.getUserMedia({
      video: { width: 640, height: 480, facingMode: "user" },
      audio: true,
    });
  } catch (e) {
    alert("Camera/microphone access was denied or not available.");
    return;
  }

  video.srcObject = camStream;
  await video.play();

  // Build the stream we will RECORD depending on the mode
  let recStream;
  blurCanvas.classList.add("hidden");
  voiceOverlay.classList.add("hidden");

  if (mode === "blur") {
    blurCanvas.width = 640; blurCanvas.height = 480;
    blurCanvas.classList.remove("hidden");
    const ctx = blurCanvas.getContext("2d");
    const draw = () => {
      ctx.filter = "blur(14px)";
      ctx.drawImage(video, 0, 0, 640, 480);
      blurRaf = requestAnimationFrame(draw);
    };
    draw();
    recStream = new MediaStream([
      ...blurCanvas.captureStream(30).getVideoTracks(),
      ...camStream.getAudioTracks(),
    ]);
    recordedIsAudio = false;
  } else if (mode === "voice") {
    voiceOverlay.classList.remove("hidden");
    recStream = new MediaStream(camStream.getAudioTracks());
    recordedIsAudio = true;
  } else {
    recStream = camStream;
    recordedIsAudio = false;
  }

  // Recorder
  chunks = []; samples = []; totalTicks = 0;
  const mimeType = pickMime(recordedIsAudio);
  recorder = new MediaRecorder(recStream, mimeType ? { mimeType } : undefined);
  recorder.ondataavailable = (e) => e.data.size && chunks.push(e.data);
  recorder.onstop = onRecorderStop;
  recorder.start(1000);

  // UI
  startTime = Date.now();
  startBtn.disabled = true;
  stopBtn.disabled = false;
  $("modes").style.pointerEvents = "none";
  $("results").classList.add("hidden");
  statusEl.textContent = "Recording… analysing expressions";

  tickTimer = setInterval(onTick, 250);
  detectTimer = setInterval(detect, DETECT_EVERY_MS);
});

// ================== TIMER ==================
function onTick() {
  const sec = Math.floor((Date.now() - startTime) / 1000);
  timerEl.textContent = fmt(sec);
  bar.style.width = Math.min(100, (sec / MAX_SECONDS) * 100) + "%";

  if (sec >= MAX_SECONDS) stopRecording();
}

// ================== DETECTION ==================
async function detect() {
  if (busy || video.readyState < 2) return;
  busy = true;
  totalTicks++;
  try {
    const res = await faceapi
      .detectSingleFace(video, new faceapi.TinyFaceDetectorOptions({ inputSize: 224, scoreThreshold: 0.5 }))
      .withFaceExpressions();
    if (res) {
      samples.push({ ...res.expressions });
      const top = Object.entries(res.expressions).sort((a, b) => b[1] - a[1])[0];
      statusEl.textContent = `Recording… detected: ${EMOJI[top[0]]} ${top[0]}`;
    } else {
      statusEl.textContent = "Recording… no face detected (face the camera, good lighting)";
    }
  } catch (e) { console.error(e); }
  busy = false;
}

// ================== STOP ==================
stopBtn.addEventListener("click", stopRecording);

function stopRecording() {
  if (!recorder || recorder.state === "inactive") return;
  clearInterval(tickTimer); clearInterval(detectTimer);
  if (blurRaf) cancelAnimationFrame(blurRaf);
  recorder.stop();
  camStream.getTracks().forEach((t) => t.stop());
  stopBtn.disabled = true;
  statusEl.textContent = "Analysing…";
}

function onRecorderStop() {
  const type = recorder.mimeType || (recordedIsAudio ? "audio/webm" : "video/webm");
  recordedBlob = new Blob(chunks, { type });
  showResults();
}

// ================== RESULTS ==================
function showResults() {
  const keys = ["neutral", "happy", "sad", "angry", "fearful", "disgusted", "surprised"];
  const avg = Object.fromEntries(keys.map((k) => [k, 0]));
  samples.forEach((s) => keys.forEach((k) => (avg[k] += s[k])));
  const n = samples.length;
  keys.forEach((k) => (avg[k] = n ? avg[k] / n : 0));

  const faceRatio = totalTicks ? n / totalTicks : 0;
  $("results").classList.remove("hidden");
  statusEl.textContent = "Done.";

  // Playback / download
  const url = URL.createObjectURL(recordedBlob);
  const pv = $("playback"), pa = $("playbackAudio");
  pv.classList.toggle("hidden", recordedIsAudio);
  pa.classList.toggle("hidden", !recordedIsAudio);
  (recordedIsAudio ? pa : pv).src = url;
  const ext = recordedBlob.type.includes("mp4") ? "mp4" : "webm";
  $("downloadLink").href = url;
  $("downloadLink").download = `safespace-session.${ext}`;

  // Not enough data
  if (n < 10 || faceRatio < 0.3) {
    $("moodEmoji").textContent = "❓";
    $("moodName").textContent = "Not enough data";
    $("moodDetail").textContent =
      "Your face was visible in too few frames. Try again facing the camera in good lighting.";
    $("bars").innerHTML = "";
    $("indicators").innerHTML = "";
    return;
  }

  // Dominant mood
  const sorted = Object.entries(avg).sort((a, b) => b[1] - a[1]);
  const [topName, topVal] = sorted[0];
  $("moodEmoji").textContent = EMOJI[topName];
  $("moodName").textContent = topName;
  $("moodDetail").textContent =
    `Face visible in ${(faceRatio * 100).toFixed(0)}% of the session · ${n} frames analysed.`;

  // Bars
  $("bars").innerHTML = sorted
    .map(([k, v]) => `
      <div class="bar-row">
        <span class="name">${EMOJI[k]} ${k}</span>
        <div class="track"><div class="fill" style="width:${(v * 100).toFixed(1)}%"></div></div>
        <span class="pct">${(v * 100).toFixed(1)}%</span>
      </div>`).join("");

  // Wellbeing indicators (rule-based, NOT a diagnosis)
  const negative = avg.sad + avg.fearful + avg.angry + avg.disgusted;
  const level = (v, lo, hi) => (v >= hi ? "high" : v >= lo ? "medium" : "low");
  const label = { low: "Low", medium: "Moderate", high: "Elevated" };

  const indicators = [
    {
      title: "Low mood signs",
      desc: "Frequent sad expressions across the session.",
      lvl: level(avg.sad, 0.15, 0.35),
    },
    {
      title: "Stress / anxiety signs",
      desc: "Frequent fearful or surprised expressions.",
      lvl: level(avg.fearful + avg.surprised * 0.5, 0.12, 0.3),
    },
    {
      title: "Irritability signs",
      desc: "Frequent angry or disgusted expressions.",
      lvl: level(avg.angry + avg.disgusted, 0.15, 0.35),
    },
    {
      title: "Flat expression (little emotion shown)",
      desc: "Mostly neutral face with very little variation.",
      lvl: level(avg.neutral - (1 - avg.neutral) * 0.2, 0.75, 0.9),
    },
    {
      title: "Overall negative emotion",
      desc: "Combined share of negative expressions.",
      lvl: level(negative, 0.25, 0.5),
    },
  ];

  $("indicators").innerHTML = indicators
    .map((i) => `<li><span class="tag ${i.lvl}">${label[i.lvl]}</span> <b>${i.title}</b><br><small>${i.desc}</small></li>`)
    .join("");

  if (indicators.some((i) => i.lvl === "high")) {
    $("indicators").innerHTML +=
      `<li><b>💚 Consider talking to someone.</b> Several indicators were elevated. Sharing this session with a therapist or doctor can help. This is not a diagnosis.</li>`;
  }
}

// ================== BUTTONS ==================
$("sendBtn").addEventListener("click", () => {
  // TODO: send recordedBlob + results to your Laravel/PHP/Node backend with fetch + FormData
  // const fd = new FormData(); fd.append("file", recordedBlob, "session.webm"); fd.append("prompt", $("prompt").value);
  // fetch("/api/recordings", { method: "POST", body: fd });
  alert("Demo: recording would now be sent to the therapist (connect your backend here).");
});

$("againBtn").addEventListener("click", () => {
  $("results").classList.add("hidden");
  $("modes").style.pointerEvents = "auto";
  startBtn.disabled = false;
  timerEl.textContent = "00:00";
  bar.style.width = "0";
  statusEl.textContent = "Ready. Choose a mode and press Start.";
  video.srcObject = null;
  blurCanvas.classList.add("hidden");
  voiceOverlay.classList.add("hidden");
});