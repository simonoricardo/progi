import { describe, it, expect } from 'vitest'
import { useDebouncedRef } from '../../src/helpers/useDebouncedRef'

describe('useDebouncedRef', () => {
  it('should debounce update the value only after a given delay', async () => {
    const delay = 100
    const debouncedRef = useDebouncedRef('initial', delay)

    // Change the value several times
    debouncedRef.value = 'first update'
    debouncedRef.value = 'second update'
    debouncedRef.value = 'third update'

    // Ensure the value is still 'initial' before the debounce triggers
    expect(debouncedRef.value).toBe('initial')

    // Wait for the debounce period (delay + a little buffer time)
    await new Promise((resolve) => setTimeout(resolve, delay + 50))

    // After the debounce, the value should have updated to the last set value
    expect(debouncedRef.value).toBe('third update')
  })
})
