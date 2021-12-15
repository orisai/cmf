<script>
	import Select from 'svelte-select';
	import Item from './SelectItem.svelte';
	import {onMount} from 'svelte';

	export let items;
	export let selectedValue;
	export let isMulti;
	export let id;
	export let name;
	export let required;
	export let disabled;
	export let placeholder;
	export let rules;
	export let container;

	let listOpen = false;
	let isFocused = false;
	let filteredItems = [];

	$: {
		if (isMulti && !listOpen && isFocused && filteredItems.length !== 0) listOpen = true
	}

	const svelteId = id + '-svelte';
	const inputAttributes = {
		'id': svelteId,
		'required': required,
		'disabled': disabled,
		'data-nette-rules': rules,
	};

	let selectItems = [];
	for (const [key, value] of Object.entries(items)) {
		selectItems.push({
			'value': key,
			'label': value,
		})
	}

	let selectSelectedItems = [];
	if (isMulti) {
		for (const [key, value] of Object.entries(selectedValue)) {
			selectSelectedItems.push({
				'value': value,
			})
		}
	} else {
		if (selectedValue !== null) {
			selectSelectedItems = items[selectedValue];
		} else {
			selectSelectedItems = null;
		}
	}

	function createSelect() {
		const select = document.createElement('select');
		select.id = id;
		select.name = name;
		select.multiple = isMulti;
		select.classList.add('hidden');
		return select;
	}

	function createOption(value) {
		const option = document.createElement('option');
		option.value = value;
		option.selected = true;
		return option;
	}

	onMount(function () {
		const select = createSelect();

		if (selectedValue !== null) {
			if (isMulti) {
				for (const [key, value] of Object.entries(selectedValue)) {
					select.appendChild(createOption(value));
				}
			} else {
				select.appendChild(createOption(selectedValue));
			}
		}

		document.getElementById(svelteId).after(select);
	});

	function handleSelect(event) {
		const select = createSelect();

		if (event.detail !== null) {
			if (isMulti) {
				event.detail.forEach(function (item) {
					select.appendChild(createOption(item.value));
				});
			} else {
				select.appendChild(createOption(event.detail.value));
			}
		}

		document.getElementById(id).replaceWith(select);
	}
</script>

<!-- todo - translate noOptionsMessage -->
<!-- todo - error classes - text-red-900 placeholder-red-300 border-red-300 focus:border-red-500 focus:ring-red-500 -->
<Select {Item}
		isMulti={isMulti}
		items={selectItems}
		selectedValue={selectSelectedItems}
		inputAttributes={inputAttributes}
		noOptionsMessage="Žádné položky"
		placeholder={placeholder}
		containerClasses="mt-1 block w-full px-3 py-2 shadow-sm border rounded-md focus:outline-none sm:text-sm bg-white border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 ori-svelte-select"
		on:select={handleSelect}
		bind:container
		bind:listOpen
		bind:isFocused
		bind:filteredItems
/>
