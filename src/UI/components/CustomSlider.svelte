<script lang="ts">
  import { type Snippet } from "svelte";

  let {
    value = $bindable(),
    ...props
  }: {
    min: number;
    max: number;
    children: Snippet;
    step?: number;
    value?: number;
    trackClass?: string;
    thumbClass?: string;
    onUserChange?: (value: number) => void;
  } = $props();

  value = value ?? props.min;

  const percent = $derived(
    (((value) - props.min) / (props.max - props.min)) * 100,
  );
  let move = $state(false);

  let track: HTMLDivElement;
  let thumb: HTMLSpanElement;

  function calculateNewValue(clientX: number) {
    const { left, width } = track.getBoundingClientRect();
    const absoluteX = clientX;
    const relativeX = absoluteX - left;
    const percentX = (relativeX / width) * 100;
    const newValue = props.min + ((props.max - props.min) * percentX) / 100;
    return Math.max(props.min, Math.min(props.max, newValue));
  }

  function onTrackMove(event: MouseEvent | TouchEvent) {
    if (!move) return;
    value = calculateNewValue(
      "touches" in event ? event.touches[0].clientX : event.clientX,
    );
    props.onUserChange?.(value);
  }

  function onTrackPress(event: MouseEvent | TouchEvent) {
    move = true;
    value = calculateNewValue(
      "touches" in event ? event.touches[0].clientX : event.clientX,
    );
    props.onUserChange?.(value);
    function onMove(event: MouseEvent | TouchEvent) {
      onTrackMove(event);
    }
    document.addEventListener("mousemove", onMove);
    document.addEventListener("touchmove", onMove);
    document.addEventListener(
      "mouseup",
      () => {
        move = false;
        document.removeEventListener("mousemove", onMove);
      },
      { once: true },
    );
    document.addEventListener(
      "touchend",
      () => {
        move = false;
        document.removeEventListener("touchmove", onMove);
      },
      { once: true },
    );
  }
</script>

<div
  bind:this={track}
  class={`relative w-3/5 h-[.5em] bg-fire/10 rounded-full ${props.trackClass ?? ''}`}
  role="slider"
  aria-valuemin={props.min}
  aria-valuemax={props.max}
  aria-valuenow={value}
  tabindex="0"
  ontouchstart={onTrackPress}
  onmousedown={onTrackPress}
>
  <div
    bind:this={thumb}
    class={`absolute left-[var(--value)] top-1/2 transform -translate-x-1/2 -translate-y-1/2 select-none text-[2em] ${props.thumbClass ?? ''} scale-[calc(75%+var(--value))]`}
    style={`--value: ${percent.toFixed(2)}%`}
  >
    {@render props.children?.()}
  </div>
</div>
