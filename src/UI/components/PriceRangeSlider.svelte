<script lang="ts">
  import { onMount } from 'svelte';
  
  export let min: number = 0;
  export let max: number = 200;
  export let values: number[] = [min, max];
  
  let sliderTrack: HTMLElement;
  let leftThumb: HTMLElement;
  let rightThumb: HTMLElement;
  let trackWidth: number;
  let isDragging = false;
  let activeBullet: HTMLElement | null = null;
  
  $: leftPosition = ((values[0] - min) / (max - min)) * 100;
  $: rightPosition = ((values[1] - min) / (max - min)) * 100;
  $: fillWidth = rightPosition - leftPosition;
  
  function setThumbPosition(e: MouseEvent | TouchEvent, thumb: HTMLElement) {
    const rect = sliderTrack.getBoundingClientRect();
    const x = getEventX(e) - rect.left;
    const percentage = Math.min(Math.max(0, x / rect.width), 1);
    const value = min + percentage * (max - min);
    
    if (thumb === leftThumb) {
      values[0] = Math.min(Math.round(value), values[1] - 1);
    } else {
      values[1] = Math.max(Math.round(value), values[0] + 1);
    }
    
    values = [...values]; // trigger reactivity
  }
  
  function getEventX(e: MouseEvent | TouchEvent): number {
    return e instanceof MouseEvent 
      ? e.clientX 
      : e.touches[0].clientX;
  }
  
  function startDrag(e: MouseEvent | TouchEvent, thumb: HTMLElement) {
    isDragging = true;
    activeBullet = thumb;
    setThumbPosition(e, thumb);
    
    const handleMove = (moveEvent: MouseEvent | TouchEvent) => {
      if (isDragging && activeBullet) {
        setThumbPosition(moveEvent, activeBullet);
      }
    };
    
    const handleEnd = () => {
      isDragging = false;
      activeBullet = null;
      window.removeEventListener('mousemove', handleMove as EventListener);
      window.removeEventListener('touchmove', handleMove as EventListener);
      window.removeEventListener('mouseup', handleEnd);
      window.removeEventListener('touchend', handleEnd);
    };
    
    window.addEventListener('mousemove', handleMove as EventListener);
    window.addEventListener('touchmove', handleMove as EventListener);
    window.addEventListener('mouseup', handleEnd);
    window.addEventListener('touchend', handleEnd);
  }
  
  function handleTrackClick(e: MouseEvent) {
    // Determine which thumb to move based on click position
    const rect = sliderTrack.getBoundingClientRect();
    const clickX = e.clientX - rect.left;
    const centerX = ((values[0] + values[1]) / 2 - min) / (max - min) * rect.width;
    
    if (clickX < centerX) {
      setThumbPosition(e, leftThumb);
    } else {
      setThumbPosition(e, rightThumb);
    }
  }
  
  onMount(() => {
    trackWidth = sliderTrack?.clientWidth || 0;
  });
</script>

<div class="price-range-container">
  <div 
    class="slider-track bg-gray-200 relative h-2 rounded-full cursor-pointer"
    bind:this={sliderTrack}
    on:click={handleTrackClick}
  >
    <div 
      class="slider-fill bg-ui-background absolute h-full rounded-full -z-10"
      style="left: {leftPosition}%; width: {fillWidth}%;"
    ></div>
    
    <div 
      class="slider-thumb w-5 h-5 bg-background border-2 border-primary rounded-full absolute top-1/2 -translate-y-1/2 -ml-2.5 cursor-grab active:cursor-grabbing shadow-md"
      style="left: {leftPosition}%"
      bind:this={leftThumb}
      on:mousedown={(e) => startDrag(e, leftThumb)}
      on:touchstart={(e) => startDrag(e, leftThumb)}
    ></div>
    
    <div 
      class="slider-thumb w-5 h-5 bg-background border-2 border-primary rounded-full absolute top-1/2 -translate-y-1/2 -ml-2.5 cursor-grab active:cursor-grabbing shadow-md"
      style="left: {rightPosition}%"
      bind:this={rightThumb}
      on:mousedown={(e) => startDrag(e, rightThumb)}
      on:touchstart={(e) => startDrag(e, rightThumb)}
    ></div>
  </div>
  
  <div class="flex justify-between mt-2">
    <div>{values[0]}€</div>
    <div>{values[1]}€</div>
  </div>
</div>