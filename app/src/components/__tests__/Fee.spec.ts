import { describe, it, expect } from 'vitest'
import { mount } from '@vue/test-utils'
import FeeComponent from '../Fee.vue'

describe('CurrencyComponent', () => {
  it('formats a number prop correctly', () => {
    const wrapper = mount(FeeComponent, {
      props: {
        number: 1234.56,
        class: 'class-from-props',
      },
    })

    expect(wrapper.text()).toBe('$1,234.56')
  })

  it('formats a string prop correctly', () => {
    const wrapper = mount(FeeComponent, {
      props: {
        number: '1234',
        class: 'class-from-props',
      },
    })

    expect(wrapper.text()).toBe('$1,234.00')
  })

  it('applies the correct class(es)', () => {
    const wrapper = mount(FeeComponent, {
      props: {
        number: 1234.56,
        class: 'class-from-props',
      },
    })

    expect(wrapper.find('p').classes()).toContain('class-from-props')
  })
})
