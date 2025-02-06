<script lang="ts">
  const props = $props<{
    width?: number;
    height?: number;
    facingMode?: boolean;
    enableCapture?: boolean;
    enableModeSwitch?: boolean;
    captureAsInput?: string;
    onCapture?: (image: string) => void;
  }>();

  let facingMode = $state<boolean>(props.facingMode || false);

  let video: HTMLVideoElement;
  let captureInput: HTMLInputElement;

  $effect(() => {
    navigator.mediaDevices
      .getUserMedia({ video: {
        width: { ideal: props.width || 720 },
        height: { ideal: props.height || 1280 },
        facingMode: facingMode ? "environment" : "user",
      } })
      .then((stream) => {
        video.srcObject = stream;
      })
      .catch((error) => {
        console.error(error);
      });
  });

  function onCaptureButtonClick() {
    const canvas = document.createElement("canvas");
    canvas.width = video.videoWidth;
    canvas.height = video.videoHeight;
    const context = canvas.getContext("2d")
    if (!context) throw new Error("Unable to get 2d context");
    context.drawImage(video, 0, 0, canvas.width, canvas.height);
    props.onCapture?.(canvas.toDataURL("image/jpeg"));
    if (props.captureAsInput) {
      const file = new File([canvas.toDataURL("image/jpeg")], "image.jpg", { type: "image/jpeg" });
      const dataTransfer = new DataTransfer();
      dataTransfer.items.add(file);
      captureInput.files = dataTransfer.files;
    }
  }
</script>

<video id="camera" class="w-full h-full" autoplay bind:this={video} />

{#if props.enableCapture}
  <button on:click|preventDefault={onCaptureButtonClick}>
    Capture
  </button>
{/if}

{#if props.enableModeSwitch}
  <button on:click|preventDefault={() => facingMode = !facingMode}>
    Switch mode
  </button>
{/if}

{#if props.captureAsInput}
  <input type="file" bind:this={captureInput} name={props.captureAsInput} />
{/if}
