<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link, usePage, router } from '@inertiajs/vue3';
import { type BreadcrumbItem } from '@/types';
import { ref, onMounted } from 'vue';
import axios from 'axios';
import $ from 'jquery';
import DataTable from 'datatables.net-dt';
import 'datatables.net-dt/css/dataTables.dataTables.css';
import Button from '@/components/ui/button/Button.vue';

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Products', href: '/products' },
];

const props = usePage().props as any;
const products = ref(props.products);
const tableRef = ref<HTMLTableElement | null>(null);

async function deleteProduct(id: number) {
    await axios.delete(`/products/${id}`);
    products.value = products.value.filter((p: any) => p.id !== id);
}

onMounted(() => {
    if (tableRef.value) {
        new DataTable(tableRef.value);
    }
});
</script>

<template>
  <Head title="Products" />

  <AppLayout :breadcrumbs="breadcrumbs">
    <div class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
      <div class="flex items-center justify-between">
        <h1>Products</h1>
        <Link href="/products/create" class="px-3 rounded-md py-1 bg-green-500 text-white"
          >Create Product</Link
        >
      </div>

      <table ref="tableRef" class="display" style="width: 100%">
        <thead>
          <tr>
            <th>ID</th>
            <th>Name</th>
            <th>SKU</th>
            <th>Price</th>
            <th>Action</th>
          </tr>
        </thead>

        <tbody>
          <tr v-for="product in products" :key="product.id">
            <td>{{ product.id }}</td>
            <td>{{ product.name }}</td>
            <td>{{ product.sku }}</td>
            <td>{{ product.price }}</td>

            <td class="flex gap-2">
              <Link
                :href="`/products/${product.id}/edit`"
                class="px-2 py-1 bg-blue-500 text-white rounded"
              >
                Edit
              </Link>

              <Button
                @click="deleteProduct(product.id)"
                class="px-2 py-1 bg-red-500 text-white rounded cursor-pointer"
                >Delete</Button
              >
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </AppLayout>
</template>
