<script lang="ts">
    interface Props {
        name: string;
    }

    const { name }: Props = $props();
	let count = $state(0);

	function increment() {
		count += 1;
	}
</script>

<h1>Hello {name}!</h1>
<button onclick={increment}>
	clicks: {count}
</button>
