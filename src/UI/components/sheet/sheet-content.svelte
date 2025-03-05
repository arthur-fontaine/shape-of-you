<script lang="ts">
	import { Dialog as SheetPrimitive } from "bits-ui";
	import { fly } from "svelte/transition";
	import {
		SheetOverlay,
		SheetPortal,
		type Side,
		sheetTransitions,
		sheetVariants,
	} from "./index.js";
	import { cn } from "./utils.js";

	type $$Props = SheetPrimitive.ContentProps & {
		side?: Side;
	};

	let className: $$Props["class"] = undefined;
	export let side: $$Props["side"] = "right";
	export { className as class };
	export let inTransition: $$Props["inTransition"] = fly;
	export let inTransitionConfig: $$Props["inTransitionConfig"] =
		sheetTransitions[side ?? "right"].in;
	export let outTransition: $$Props["outTransition"] = fly;
	export let outTransitionConfig: $$Props["outTransitionConfig"] =
		sheetTransitions[side ?? "right"].out;
</script>

<SheetPortal>
	<SheetOverlay />
	<SheetPrimitive.Content
		{inTransition}
		{inTransitionConfig}
		{outTransition}
		{outTransitionConfig}
		class={cn(sheetVariants({ side }), className)}
		{...$$restProps}
	>
		<slot />
		<SheetPrimitive.Close
			class="data-[state=open]:bg-secondary absolute right-4 top-4 rounded-sm opacity-70 transition-opacity hover:opacity-100 focus:outline-none disabled:pointer-events-none"
		>
			<span class="icon-[tabler--x] text-2xl"></span>
			<span class="sr-only">Close</span>
		</SheetPrimitive.Close>
	</SheetPrimitive.Content>
</SheetPortal>
