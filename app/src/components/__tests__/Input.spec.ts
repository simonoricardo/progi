import { describe, it, expect } from 'vitest'
import { mount } from '@vue/test-utils'
import InputComponent from '../Input.vue'

describe('InputComponent', () => {
  it('binds v-model correctly', async () => {
    const wrapper = mount(InputComponent, {
      props: {
        type: 'text',
        placeholder: 'Enter value',
      },
    })

    const input = wrapper.find('input')
    await input.setValue('123')

    expect(wrapper.vm.value).toBe('123')
  })

  it('applies type and placeholder props correctly', () => {
    const wrapper = mount(InputComponent, {
      props: {
        type: 'number',
        placeholder: 'Enter a number',
      },
    })

    const input = wrapper.find('input')

    expect(input.attributes('type')).toBe('number')
    expect(input.attributes('placeholder')).toBe('Enter a number')
  })

  it('applies error styling and displays error message when errors are provided', () => {
    const wrapper = mount(InputComponent, {
      props: {
        type: 'text',
        placeholder: 'Enter value',
        errors: ['Invalid input'],
      },
    })

    const input = wrapper.find('input')
    const errorMessage = wrapper.find('p')

    expect(input.classes()).toContain('border-red-400')

    expect(errorMessage.text()).toBe('Invalid input')
  })

  it('does not apply error styling or display error message when errors are not provided', () => {
    const wrapper = mount(InputComponent, {
      props: {
        type: 'text',
        placeholder: 'Enter value',
        errors: null,
      },
    })

    const input = wrapper.find('input')
    const errorMessage = wrapper.find('p')

    expect(input.classes()).not.toContain('border-red-400')

    expect(errorMessage.exists()).toBe(false)
  })

  it('handles dynamic updates to props', async () => {
    const wrapper = mount(InputComponent, {
      props: {
        type: 'text',
        placeholder: 'Enter value',
        errors: null,
      },
    })

    await wrapper.setProps({
      type: 'number',
      placeholder: 'Enter a number',
      errors: ['New error'],
    })

    const input = wrapper.find('input')
    const errorMessage = wrapper.find('p')

    expect(input.attributes('type')).toBe('number')
    expect(input.attributes('placeholder')).toBe('Enter a number')

    expect(input.classes()).toContain('border-red-400')
    expect(errorMessage.text()).toBe('New error')
  })
})
