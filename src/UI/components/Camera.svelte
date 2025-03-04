<script lang="ts">
  import type { Snippet } from "svelte";

  const props: {
    width?: number;
    height?: number;
    facingMode?: boolean;
    enableCapture?: boolean;
    enableModeSwitch?: boolean;
    captureAsInput?: string;
    onCapture?: (image: string) => void;
    children: Snippet;
    capturedImage?: string;
  } = $props();

  let facingMode = $state<boolean>(props.facingMode || false);

  let video: HTMLVideoElement;
  let captureInput: HTMLInputElement;

  $effect(() => {
    navigator.mediaDevices
      .getUserMedia({
        video: {
          width: { ideal: props.width || 720 },
          height: { ideal: props.height || 1280 },
          facingMode: facingMode ? "environment" : "user",
        },
      })
      .then((stream) => {
        video.srcObject = stream;
      })
      .catch((error) => {
        console.error(error);
      });
  });

  function onCaptureButtonClick(e: MouseEvent) {
    e.preventDefault();
    const canvas = document.createElement("canvas");
    canvas.width = video.videoWidth;
    canvas.height = video.videoHeight;
    const context = canvas.getContext("2d");
    if (!context) throw new Error("Unable to get 2d context");
    context.drawImage(video, 0, 0, canvas.width, canvas.height);
    props.onCapture?.(canvas.toDataURL("image/jpeg"));
    if (props.captureAsInput) {
      const file = new File([canvas.toDataURL("image/jpeg")], "image.jpg", {
        type: "image/jpeg",
      });
      const dataTransfer = new DataTransfer();
      dataTransfer.items.add(file);
      captureInput.files = dataTransfer.files;
    }
  }

  function switchMode(e: MouseEvent) {
    e.preventDefault();
    facingMode = !facingMode;
  }
</script>

{#if props.capturedImage}
  <img
    src={props.capturedImage}
    alt="Captured"
    class="w-full h-full object-cover"
  />
{:else}
  <video
    id="camera"
    class="w-full h-full object-cover"
    autoplay
    bind:this={video}
  >
    <track kind="captions" class="hidden" />
  </video>
{/if}

<div class="absolute bottom-0 gap-4 p-14 w-full *:z-2 after:content-[''] after:block after:bg-gradient-to-b after:from-transparent after:to-ui-background/70 after:absolute after:w-full after:h-full after:z-1 after:top-0 after:left-0">
  <div
    class="left-1/4 top-1/2 transform -translate-x-1/2 -translate-y-1/2 absolute text-3xl"
  >
    {@render props.children?.()}
  </div>

  {#if props.enableCapture}
    <button
      onclick={onCaptureButtonClick}
      aria-label="Capturer"
      class="left-1/2 top-1/2 transform -translate-x-1/2 -translate-y-1/2 absolute"
    >
      <div
        class="size-14 flex items-center justify-center p-1 bg-ui-surface rounded-full"
      >
        <div class="w-full h-full bg-border rounded-full shadow-inner"></div>
      </div>
    </button>
  {/if}

  {#if props.enableModeSwitch}
    <button
      onclick={switchMode}
      aria-label="Changer de caméra"
      class="left-3/4 top-1/2 transform -translate-x-1/2 -translate-y-1/2 absolute"
    >
      <span class="icon-[tabler--camera-rotate] text-ui-surface text-3xl flex"
      ></span>
    </button>
  {/if}
</div>

{#if props.captureAsInput}
  <input
    type="file"
    bind:this={captureInput}
    name={props.captureAsInput}
    class="hidden"
  />
{/if}
