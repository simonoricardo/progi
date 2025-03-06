import { ref } from 'vue'
import { describe, it, expect, vi, beforeEach } from 'vitest'
import { mount } from '@vue/test-utils'
import HomeView from '../src/views/HomeView.vue'
import { fetchFeesData } from '../src/api/fees'

vi.mock('../src/api/fees')
vi.mock('../src/helpers/useDebouncedRef', () => ({
  useDebouncedRef: (initialValue = 200) => ref(initialValue),
}))

describe('AuctionView', () => {
  beforeEach(() => {
    vi.clearAllMocks()
  })

  it('renders correctly', () => {
    const wrapper = mount(HomeView)

    expect(wrapper.find('h1').text()).toBe('Auction calculator')
    expect(wrapper.findComponent({ name: 'Select' }).exists()).toBe(true)
    expect(wrapper.findComponent({ name: 'Input' }).exists()).toBe(true)
    expect(wrapper.findComponent({ name: 'FeesList' }).exists()).toBe(true)
  })

  it('updates select and input values and triggers API call', async () => {
    const mockData = {
      auction: {
        totalFees: 0,
        feesList: { Basic: 0, Seller: 0, Association: 0, Storage: 0 },
        totalVehicleValue: 0,
        vehicleType: 'luxury',
        vehicleValue: 2000,
      },
    }
    vi.mocked(fetchFeesData).mockResolvedValue(mockData)

    const wrapper = mount(HomeView)

    const select = wrapper.findComponent({ name: 'Select' })
    const input = wrapper.findComponent({ name: 'Input' })

    await select.setValue('common')
    await input.setValue('50000')

    await new Promise((resolve) => setTimeout(resolve, 300))

    expect(fetchFeesData).toHaveBeenCalledWith({
      vehicleType: 'common',
      vehicleValue: '50000',
    })

    expect(wrapper.vm.auctionRef).toEqual(mockData.auction)
    expect(wrapper.findComponent({ name: 'FeesList' }).props('auction')).toEqual(mockData.auction)
  })

  it('handles API errors and propagates them to inputs', async () => {
    const mockErrors = {
      errors: { vehicleType: ['Invalid type'], vehicleValue: ['Invalid value'] },
    }

    vi.mocked(fetchFeesData).mockResolvedValue(mockErrors)

    const wrapper = mount(HomeView)

    const select = wrapper.findComponent({ name: 'Select' })
    const input = wrapper.findComponent({ name: 'Input' })

    await select.setValue('invalid')
    await input.setValue('invalid')

    await new Promise((resolve) => setTimeout(resolve, 300))

    expect(fetchFeesData).toHaveBeenCalledWith({
      vehicleType: 'invalid',
      vehicleValue: 'invalid',
    })

    expect(wrapper.vm.selectErrorsRef).toEqual(mockErrors.errors.vehicleType)
    expect(wrapper.vm.inputErrorsRef).toEqual(mockErrors.errors.vehicleValue)
    expect(wrapper.findComponent({ name: 'Select' }).props('errors')).toEqual(
      mockErrors.errors.vehicleType,
    )
    expect(wrapper.findComponent({ name: 'Input' }).props('errors')).toEqual(
      mockErrors.errors.vehicleValue,
    )
  })

  it('does not trigger API call until both inputs are dirty', async () => {
    const wrapper = mount(HomeView)

    const select = wrapper.findComponent({ name: 'Select' })
    await select.setValue('common')

    await new Promise((resolve) => setTimeout(resolve, 300))

    expect(fetchFeesData).not.toHaveBeenCalled()

    const input = wrapper.findComponent({ name: 'Input' })
    await input.setValue('50000')

    await new Promise((resolve) => setTimeout(resolve, 300))

    expect(fetchFeesData).toHaveBeenCalled()
  })
})
