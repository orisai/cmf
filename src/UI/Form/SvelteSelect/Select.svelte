<script lang="ts">
	import {onMount} from 'svelte';
	import Select from 'svelte-select';

	export let netteItems: Object;
	export let netteValue: string[] | string | null;
	export let hasErrors: boolean;
	export let multiple: boolean;
	export let id: string;
	export let name: string;
	export let required: boolean;
	export let disabled: boolean;
	export let placeholder: string;
	export let rules: Object[];

	let checked: string[] = [];
	let isChecked: Record<string, boolean> = {};
	let items: Object[] = [];
	let value = [];

	$: computeValue(checked);
	$: computeIsChecked(checked);

	const svelteId = id + '-svelte';
	const inputAttributes = {
		'id': svelteId,
		'required': required,
		'disabled': disabled,
		'data-nette-rules': rules,
	};

	let selectClasses = 'ori-svelte-select mt-1 block w-full px-3 py-2 shadow-sm border rounded-md focus:outline-none sm:text-sm bg-white';
	// TODO - not fully working, some parts of select have higher priority
	if (hasErrors) {
		selectClasses += ' text-red-900 placeholder-red-300 border-red-300 focus:border-red-500 focus:ring-red-500';
	} else {
		selectClasses += ' border-gray-300 focus:border-indigo-500 focus:ring-indigo-500';
	}

	for (const [key, item] of Object.entries(netteItems)) {
		items.push({
			'value': key,
			'label': item,
		})
	}

	if (multiple) {
		for (const [key, item] of Object.entries(netteValue)) {
			value.push({
				'value': item,
			})
			checked.push(item);
		}
	} else {
		if (netteValue !== null) {
			value = netteItems[netteValue];
			checked.push(netteValue);
		} else {
			value = null;
		}
	}

	onMount(function (): void {
		const select = createSelect();

		if (netteValue !== null) {
			if (typeof netteValue === 'string') {
				select.appendChild(createOption(netteValue));
			} else {
				for (const [key, item] of Object.entries(netteValue)) {
					select.appendChild(createOption(item));
				}
			}
		}

		document.getElementById(svelteId).after(select);
	});

	function createSelect(): HTMLSelectElement {
		const select = document.createElement('select');
		select.id = id;
		select.name = name;
		select.multiple = multiple;
		select.classList.add('hidden');

		return select;
	}

	function createOption(item: string): HTMLOptionElement {
		const option = document.createElement('option');
		option.value = item;
		option.selected = true;

		return option;
	}

	function handleChange(event: CustomEvent): void {
		const select = document.getElementById(id);

		if (event.type === 'clear'
			// TODO - workaround - removing triggers select instead of clear event
			// https://svelte-select-examples.vercel.app/examples/advanced/multi-item-checkboxes
			|| (!Array.isArray(event.detail) && isChecked[event.detail.value])
		) {
			if (Array.isArray(event.detail)) {
				checked = [];
				select.querySelectorAll(`option`).forEach((option: HTMLOptionElement) => {
					select.removeChild(option);
				});
			} else {
				select.querySelectorAll(`[value="${event.detail.value}"]`).forEach((option: HTMLOptionElement) => {
					select.removeChild(option);
				});
			}
		} else {
			if (select.querySelector(`[value="${event.detail.value}"]`) === null) {
				select.appendChild(createOption(event.detail.value));
			}
		}

		if (!Array.isArray(event.detail)) {
			checked.includes(event.detail.value)
				? (checked = checked.filter((i) => i != event.detail.value))
				: (checked = [...checked, event.detail.value]);
		}
	}

	function computeIsChecked(checked: string[]): void {
		isChecked = {};
		checked.forEach((c) => (isChecked[c] = true));
	}

	function computeValue(checked: string[]): void {
		if (!multiple) {
			return;
		}

		value = checked.map((c) => items.find((i) => i.value === c));
	}

</script>

<style lang="postcss">
	:global(.ori-svelte-select .value-container) {
		column-gap: 0 !important;
		padding: 2px 0 !important;
	}

	:global(.ori-svelte-select .svelte-select-list) {
		font-size: 14px !important;

		& input[type=checkbox] {
			border-radius: .25rem;
			height: 1rem;
			width: 1rem;
			color: rgb(79 70 229 / 1);
		}
	}

	:global(.ori-svelte-select.multi .value-container input) {
		margin-left: 8px !important;
	}

	:global(.ori-svelte-select .multi-item) {
		background: transparent !important;
		padding: 0 !important;
	}

	:global(.ori-svelte-select .multi-item ~ .multi-item::before) {
		content: ', ';
	}

	:global(.ori-svelte-select .multi-item):not(.active) {
		outline-color: transparent !important;
	}

	:global(.ori-svelte-select .multi-item-clear) {
		display: none !important;
	}

	:global(.ori-svelte-select .multi-item-text) {
		font-size: 14px !important;
	}

	.item {
		pointer-events: none;
	}
</style>

<Select
		items={items}
		value={value}
		multiple={multiple}
		closeListOnChange={!multiple}
		filterSelectedItems={false}
		inputAttributes={inputAttributes}
		placeholder={placeholder}
		class={selectClasses}
		on:select={handleChange}
		on:clear={handleChange}
>
	<div class="item" slot="item" let:item>
		{#if multiple}
			<label for={item.value}>
				<input type="checkbox" id={item.value} bind:checked={isChecked[item.value]}/>
				{item.label}
			</label>
		{:else}
			{item.label}
		{/if}
	</div>
</Select>
