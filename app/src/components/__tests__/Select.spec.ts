import { describe, it, expect } from 'vitest'
import { mount } from '@vue/test-utils'
import SelectComponent from '../Select.vue' // Adjust the import path

describe('SelectComponent', () => {
  it('binds v-model correctly', async () => {
    const wrapper = mount(SelectComponent, {
      props: {
        options: [
          { value: '1', label: 'Option 1' },
          { value: '2', label: 'Option 2' },
        ],
      },
    })

    const select = wrapper.find('select')
    await select.setValue('1')

    expect(wrapper.vm.value).toBe('1')
  })

  it('renders options correctly', () => {
    const options = [
      { value: '1', label: 'Option 1' },
      { value: '2', label: 'Option 2' },
    ]
    const wrapper = mount(SelectComponent, {
      props: {
        options,
      },
    })

    const selectOptions = wrapper.findAll('option')

    expect(selectOptions.length).toBe(options.length)
    options.forEach((option, index) => {
      expect(selectOptions[index].element.value).toBe(option.value)
      expect(selectOptions[index].text()).toBe(option.label)
    })
  })

  it('renders placeholder when provided', () => {
    const wrapper = mount(SelectComponent, {
      props: {
        options: [
          { value: '1', label: 'Option 1' },
          { value: '2', label: 'Option 2' },
        ],
        placeholder: 'Select an option',
      },
    })

    const selectOptions = wrapper.findAll('option')

    expect(selectOptions[0].text()).toBe('Select an option')
    expect(selectOptions[0].element.disabled).toBe(true)
  })

  it('does not render placeholder when not provided', () => {
    const wrapper = mount(SelectComponent, {
      props: {
        options: [
          { value: '1', label: 'Option 1' },
          { value: '2', label: 'Option 2' },
        ],
      },
    })

    const selectOptions = wrapper.findAll('option')

    expect(selectOptions[0].element.disabled).not.toBe(true)
  })

  it('handles dynamic updates', async () => {
    const wrapper = mount(SelectComponent, {
      props: {
        options: [
          { value: '1', label: 'Option 1' },
          { value: '2', label: 'Option 2' },
        ],
        placeholder: 'Choose an option',
      },
    })

    await wrapper.setProps({
      options: [
        { value: '3', label: 'Option 3' },
        { value: '4', label: 'Option 4' },
      ],
    })

    const selectOptions = wrapper.findAll('option')

    expect(selectOptions.length).toBe(3)
    expect(selectOptions[0].text()).toBe('Choose an option')
    expect(selectOptions[1].text()).toBe('Option 3')
    expect(selectOptions[2].text()).toBe('Option 4')
  })
})
