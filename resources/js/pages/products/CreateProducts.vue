<script setup lang="ts">
    import { ref } from 'vue'
    import { Head, Link } from '@inertiajs/vue3'

    import AppLayout from '@/layouts/AppLayout.vue'
    import { type BreadcrumbItem } from '@/types'
    import toast from "vue-hot-toast";
    import Button from '@/components/ui/button/Button.vue'
    import Input from '@/components/ui/input/Input.vue'
    import { Textarea } from '@/components/ui/textarea'
    import {
      Dialog,
      DialogContent,
      DialogHeader,
      DialogTitle,
      DialogDescription,
    } from '@/components/ui/dialog'

    const breadcrumbs: BreadcrumbItem[] = [
      { title: 'Products', href: '/products' },
      { title: 'Create Product', href: '/products/create' },
    ]



    const name = ref('')
    const sku = ref('')
    const price = ref<number | null>(null)
    const stock = ref<number | null>(null)
    const description = ref('')
    const image = ref<File | null>(null)

    const preview = ref<string | null>(null)

    function handleImageUpload(e: Event) {
      const target = e.target as HTMLInputElement
      const file = target.files?.[0]

      if (!file) return

      image.value = file
      preview.value = URL.createObjectURL(file)
}

import axios from 'axios'
import { router } from '@inertiajs/vue3'

const isSubmitting = ref(false)

async function submitProduct() {
  try {
    isSubmitting.value = true

    const formData = new FormData()
    formData.append('name', name.value)
    formData.append('sku', sku.value)
    formData.append('price', price.value?.toString() || '')
    formData.append('stock', stock.value?.toString() || '')
    formData.append('description', description.value || '')

    if (image.value) {
      formData.append('thumbnail', image.value)
    }

    await axios.post('/products', formData, {
      headers: { 'Content-Type': 'multipart/form-data' },
    })

    router.visit('/products')

  } catch (error) {
    alert(Object.values(error?.response?.data?.errors)[0]);
    console.error(error)
  } finally {
    isSubmitting.value = false
  }
}
</script>

<template>
  <Head title="Create Product" />

  <AppLayout :breadcrumbs="breadcrumbs">
    <div class="max-w-3xl mx-auto bg-white rounded-xl shadow p-8 mt-10 space-y-8">
      <header class="flex items-center justify-between">
        <h1 class="text-3xl font-bold tracking-tight">Create Product</h1>
        <Link href="/products" class="px-3 rounded-md py-1 bg-green-500 text-white"
          >View Products</Link
        >
      </header>

      <div class="space-y-6">
        <div class="space-y-2">
          <label class="font-medium">Product Name</label>
          <Input v-model="name" placeholder="Product name" />
        </div>

        <div class="space-y-2">
          <label class="font-medium">SKU (must be unique)</label>
          <Input v-model="sku" placeholder="SKU12345" />
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
          <div class="space-y-2">
            <label class="font-medium">Price</label>
            <Input type="number" step="0.01" v-model="price" placeholder="0.00" />
          </div>

          <div class="space-y-2">
            <label class="font-medium">Stock Quantity</label>
            <Input type="number" v-model="stock" placeholder="0" />
          </div>
        </div>

        <div class="space-y-2">
          <label class="font-medium">Description</label>
          <Textarea
            v-model="description"
            placeholder="Enter product details..."
            rows="4"
          />
        </div>

        <div class="space-y-3">
          <label class="font-medium">Product Image</label>

          <Input type="file" accept="image/*" @change="handleImageUpload" />

          <div v-if="preview" class="pt-2">
            <img
              :src="preview"
              class="w-32 h-32 rounded border object-cover"
              alt="Preview"
            />
          </div>
        </div>

        <div class="flex justify-end gap-3 pt-6 border-t">
          <Link href="/products">
            <Button variant="secondary">Cancel</Button>
          </Link>

          <Button :disabled="isSubmitting" @click="submitProduct">
            {{ isSubmitting ? "Saving..." : "Save Product" }}
          </Button>
        </div>
      </div>
    </div>
  </AppLayout>
</template>
