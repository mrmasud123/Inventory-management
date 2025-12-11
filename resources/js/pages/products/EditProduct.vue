<script setup lang="ts">
import AppLayout from "@/layouts/AppLayout.vue";
import { Head, Link, usePage, router } from "@inertiajs/vue3";
import { ref } from "vue";
import Input from "@/components/ui/input/Input.vue";
import Button from "@/components/ui/button/Button.vue";
import { Textarea } from "@/components/ui/textarea";
import { toast } from "vue-hot-toast";
import axios from "axios";
const props = usePage().props as any;
const product = props.product;

const breadcrumbs = [
  { title: "Products", href: "/products" },
  { title: "Edit Product", href: `/products/${product.id}/edit` },
];

const name = ref(product.name);
const sku = ref(product.sku);
const price = ref(product.price);
const stock = ref(product.stock);
const description = ref(product.description);
const image = ref<File | null>(null);

const preview = ref(product.thumbnail_path ? `/storage/${product.thumbnail_path}` : null);

function handleImageUpload(e: Event) {
  const target = e.target as HTMLInputElement;
  const file = target.files?.[0];

  if (!file) return;

  image.value = file;
  preview.value = URL.createObjectURL(file);
}

const isSubmitting = ref(false);

async function updateProduct() {
  console.log("Updating");
  try {
    isSubmitting.value = true;

    const formData = new FormData();
    formData.append("name", name.value);
    formData.append("sku", sku.value);
    formData.append("price", price.value?.toString() || "");
    formData.append("stock", stock.value?.toString() || "");
    formData.append("description", description.value || "");
    formData.append("_method", "PUT");

    if (image.value) {
      formData.append("thumbnail", image.value);
    }

    await axios.post(`/products/${product.id}`, formData, {
      headers: { "Content-Type": "multipart/form-data" },
    });

    // toast.success("Product updated successfully!");
    alert("Product updated successfully!");
    router.visit("/products");
  } catch (error: any) {
    alert(Object.values(error?.response?.data?.errors)[0]);
  } finally {
    isSubmitting.value = false;
  }
}
</script>

<template>
  <Head title="Edit Product" />

  <AppLayout :breadcrumbs="breadcrumbs">
    <div class="max-w-3xl mx-auto bg-white rounded-xl shadow p-8 mt-10 space-y-8">
      <header class="flex items-center justify-between">
        <h1 class="text-3xl font-bold tracking-tight">Edit Product</h1>
        <Link href="/products" class="px-3 rounded-md py-1 bg-green-500 text-white">
          View Products
        </Link>
      </header>

      <div class="space-y-6">
        <div class="space-y-2">
          <label class="font-medium">Product Name</label>
          <Input v-model="name" />
        </div>

        <div class="space-y-2">
          <label class="font-medium">SKU</label>
          <Input v-model="sku" />
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
          <div class="space-y-2">
            <label class="font-medium">Price</label>
            <Input v-model="price" type="number" step="0.01" />
          </div>

          <div class="space-y-2">
            <label class="font-medium">Stock</label>
            <Input v-model="stock" type="number" />
          </div>
        </div>

        <div class="space-y-2">
          <label class="font-medium">Description</label>
          <Textarea v-model="description" rows="4" />
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

          <Button :disabled="isSubmitting" @click="updateProduct">
            {{ isSubmitting ? "Updating..." : "Update Product" }}
          </Button>
        </div>
      </div>
    </div>
  </AppLayout>
</template>
