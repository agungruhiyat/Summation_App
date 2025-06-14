<link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

<script>
    // Menggunakan writable store untuk menyimpan nilai input form
    import moment from 'moment';
    import { onMount } from "svelte"
    import {Api} from "../../libs/api";
    import Swal from "sweetalert2";

    export let scoped = ''

    let button_close = '';
    let transactions = []
    let customer = '';
    let name_product = '';
    let price = '';
    let total_product = '';
    let total_price  = '';
    let total_pay = '';
    let change_money = '';
    let unixTimestamp = 1693987200;
    let idToUpdate = null //
    let total_page = 0
    let current_page = 0
    let pages = []


    $:total_price = total_product * price;
    $:change_money = total_price - total_pay;

    getData()

    function resetValue() {
        customer = ''
        name_product = ''
        price = ''
        total_product = ''
        total_price = ''
        total_pay = ''
        change_money = ''
        idToUpdate = null // meyimpan id jika updTE

    }

    // Fungsi untuk menangani submit
    async    function handleSubmit() {
        let data = {
            customer,
            name_product,
            price,
            total_product,
            total_price,
            total_pay,
            change_money,
        }


        try {
            if (idToUpdate){
                // Update data
                await Api.put('transactions/' + idToUpdate, data)
            } else {
                await Api.post('transactions', data);
            }

            const action = idToUpdate ? 'Diupdate' : 'Disimpan'
            Swal.fire({
                title: "selmat data telah berhasil di simpan!",
                text: "berhasil" + action,
                icon: "success"
            });
            //tututp modal setelah berhasil submit
            button_close.click();
            getData()
        } catch (error) {
            console.log("errorna naon",error)
        }


        idToUpdate = null
    }

    //fungsi untuk edit data
    function editTransaction(transaction) {
        (customer = transaction.customer),
        (name_product = transaction.name_product),
        (price = transaction.price),
        (total_product = transaction.total_product),
        (total_price= transaction.total_price),
        (total_pay = transaction.total_pay),
        (change_money = transaction.change_money),


        //simpan id dari item yang akan di -update
        (idToUpdate = transaction.id)

        const Modal = new bootstrap.Modal(document.getElementById('create-update-modal'))
        Modal.show()
    }

    async function getData(page = 1, search = '') {
            let res = await Api.get ('transactions?page=' + page + '&search=' + search)
            console.log('response', res)
            transactions = res.data.transactions.data
            total_page = res.data.transactions.last_page
            current_page = res.data.transactions.current_page
            console.log('total_page','total_page')
            pages = []
            for (let i = 1; i <= total_page;i++) {
                pages.push(i)
            }
            pages = pages
            console.log('pages','pages')
        }


    // Fungsi untuk menangani pencarian
  function handleSearch() {
    getData(1, searchQuery); // Cari dari halaman 1 setiap kali ada input pencarian
  }

    onMount(() => {
        console.log("geus nampil")
        console.log('button_close', button_close)
    })

    // Fungsi untuk mengubah Unix Timestamp menjadi format tanggal/bulan/tahun
   function formatDate(timestamp) {
       // Konversi Unix timestamp ke milidetik
       const date = moment.unix(timestamp);
       // Format tanggal ke dd/MM/yyyy
       return date.format('DD-MMM-YYYY');
   }

    // delete

    async function handleDelete(id) {
        try {
            await Api.delete(`transactions/${id}`);
            Swal.fire({
                title: "Deleted!",
                text: "Data berhasil dihapus.",
                icon: "success"
            });
            getData();
        } catch (error) {
            console.error("Error deleting data:", error);
        }
    }

</script>




<!-- Button trigger modal -->
<!-- <div class="d-flex border border-1 justify-content-end">
    <button type="button" class="btn btn-light " data-bs-toggle="modal" data-bs-target="#create-update-modal" on:click={resetValue}>
        <i class="fa-solid fa-plus"></i>
    </button>

</div> -->
<div class="d-flex border border-1 justify-content-end">
<div class="action-bar">
    <button type="button" class="btn-add" data-bs-toggle="modal" data-bs-target="#create-update-modal" on:click={resetValue}>
        <i class="fa-solid fa-plus"></i> Add Transaction
    </button>
</div>
</div>


<!-- Modal -->

 <div class="modal fade custom-fade" id="create-update-modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content custom-modal">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Tambah Transaksi</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" bind:this={button_close}></button>
      </div>

      <div class="modal-body">
        <form on:submit|preventDefault={handleSubmit}>
          <div class="mb-3">
            <label for="customer" class="form-label">Nama Pembeli</label>
            <input type="text" id="customer" class="form-control" bind:value={customer} placeholder="Masukkan nama pembeli..." />
          </div>

          <div class="mb-3">
            <label for="name_product" class="form-label">Nama Produk</label>
            <input type="text" id="name_product" class="form-control" bind:value={name_product} placeholder="Masukkan nama produk..." />
          </div>

          <div class="mb-3">
            <label for="price" class="form-label">Harga</label>
            <input type="text" id="price" class="form-control" bind:value={price} placeholder="Masukkan harga produk..." />
          </div>

          <div class="mb-3">
            <label for="total_product" class="form-label">Jumlah Produk</label>
            <input type="text" id="total_product" class="form-control" bind:value={total_product} placeholder="Jumlah produk dibeli..." />
          </div>

          <div class="mb-3">
            <label for="total_price" class="form-label">Total Harga</label>
            <input type="text" id="total_price" class="form-control" bind:value={total_price} placeholder="Total harga semua produk..." />
          </div>

          <div class="mb-3">
            <label for="total_pay" class="form-label">Total Bayar</label>
            <input type="text" id="total_pay" class="form-control" bind:value={total_pay} placeholder="Jumlah uang pembayaran..." />
          </div>

          <div class="mb-3">
            <label for="change_money" class="form-label">Uang Kembalian</label>
            <input type="text" id="change_money" class="form-control" bind:value={change_money} placeholder="Uang kembalian..." />
          </div>
        </form>
      </div>

      <div class="modal-footer custom-footer">
        <button type="button" class="btn-cancel" data-bs-dismiss="modal">Tutup</button>
        <button type="button" class="btn-save" on:click={handleSubmit}>Simpan</button>
      </div>
    </div>
  </div>
</div>



<!-- table data -->
<div class="card-container">
    <div class="custom-card">
        <table class="custom-table">
            <caption>List of User</caption>
            <thead>
                <tr>

                    <th>No</th>
                    <th>Nama Pembeli</th>
                    <th>Tanggal Dibuat</th>
                    <th>Nama Produk</th>
                    <th>Harga</th>
                    <th>Total Produk</th>
                    <th>Total Harga</th>
                    <th>Total Bayar</th>
                    <th>Uang Kembalian</th>
                    <th>Edit</th>
                    <th>Hapus</th>
                </tr>
            </thead>
            <tbody>
                {#if transactions.length}
                    {#each transactions as transaction, i}
                        <tr>

                            <td>{i + 1}</td>
                            <td>{transaction.customer}</td>
                            <td>{formatDate(transaction.created_at)}</td>
                            <td>{transaction.name_product}</td>
                            <td>{transaction.price}</td>
                            <td>{transaction.total_product}</td>
                            <td>{transaction.total_price}</td>
                            <td>{transaction.total_pay}</td>
                            <td>{transaction.change_money}</td>
                            <td>
                                <button class="btn-edit" on:click={() => editTransaction(transaction)}>
                                    <i class="bx bx-pencil"></i>
                                </button>
                            </td>
                            <td>
                                <button class="btn-delete" on:click={() => handleDelete(transaction.id)}>
                                    <i class="bx bx-trash"></i>
                                </button>
                            </td>
                        </tr>
                    {/each}
                {:else}
                    <tr>
                        <td colspan="12" class="no-data">Tidak ada data yang ditemukan.</td>
                    </tr>
                {/if}
            </tbody>
        </table>

    </div>

    </div>

<!-- Pagination -->
<div class="pagination-container">

    <div class="total-info">
        Total: {transactions.length} transaksi
    </div>
</div>


<div class="d-flex justify-content-end">
        {#each pages as value}
        <button type="button" class="btn btn-sm mx-2 {value === current_page ? 'btn-primary' : ''}" on:click={() => getData(value)}>{value}</button>
    {/each}
</div>


<!-- style css -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
<style>
    /* card */
    .card-container {
    padding: 20px;
    display: flex;
    justify-content: center;
}

.custom-card {
    background: #ffffff;
    border-radius: 25px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1); /* shadow ringan */
    padding: 20px;
    width: 100%;
    max-width: 1200px;
    overflow-x: auto;
}

.custom-modal {
    font-family: 'Poppins', 'Inter', 'Segoe UI', sans-serif;
    border-radius: 10px;
    overflow: hidden;
    animation: fadeIn 0.4s ease;
}

.modal-header {
    background-color: #f5f7fa;
    border-bottom: 1px solid #e0e0e0;
}

.modal-title {
    font-size: 18px;
    font-weight: 600;
    color: #333;
}

.modal-body {
    background-color: #ffffff;
    padding: 20px;
}

.form-label {
    font-weight: 500;
    color: #555;
    font-size: 14px;
    margin-bottom: 5px;
}

.form-control {
    border-radius: 8px;
    font-size: 13px;
    padding: 10px;
    border: 1px solid #ccc;
}

.form-control::placeholder {
    color: #aaa;
    font-size: 12px;
}

.modal-footer.custom-footer {
    background-color: #f5f7fa;
    border-top: 1px solid #e0e0e0;
    display: flex;
    justify-content: flex-end;
    gap: 10px;
    padding: 15px 20px;
}

.btn-cancel {
    background-color: #e0e0e0;
    color: #333;
    border: none;
    padding: 8px 16px;
    border-radius: 8px;
    font-weight: 600;
    transition: background-color 0.3s;
}

.btn-cancel:hover {
    background-color: #d0d0d0;
}

.btn-save {
    background-color: #1890ff;
    color: #fff;
    border: none;
    padding: 8px 18px;
    border-radius: 8px;
    font-weight: 600;
    transition: background-color 0.3s;
}

.btn-save:hover {
    background-color: #40a9ff;
}

/* Fade In Animation */
@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(-20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* booton modal */
.action-bar {
    display: flex;
    justify-content: flex-end;

    padding: 10px;


    border-radius: 8px;
}

.btn-add {
    background-color: #1890ff;
    color: white;
    padding: 10px 18px;
    border: none;
    border-radius: 8px;
    font-size: 14px;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 8px;
    cursor: pointer;
    transition: all 0.3s ease;
}

.btn-add i {
    font-size: 16px;
}

.btn-add:hover {
    background-color: #40a9ff;
    transform: scale(1.05);
}

        .container {
            background-color: white;
            padding: 2rem;
            border-radius: 15px;
            box-shadow: 0px 4px 20px rgba(0, 0, 0, 0.1);
            max-width: 800px;
            margin-top: 50px;
        }

        .form-title {
            font-size: 24px;
            font-weight: bold;
            color: #FF9800;
            text-align: center;
            margin-bottom: 20px;
        }

        .form-control,
        .form-select {
            border-radius: 10px;

        }

        .handleSubmit {
            margin-top: 30px;
            text-align: center;
        }

        .handleSubmit button {
            background-color: #FF9800;
            color: white;
            border: none;
            padding: 10px 40px;
            border-radius: 10px;
        }

        .handleSubmit button:hover {
            background-color: #e58900;
        }

        .custom-table {
       width: 100%;
    border-collapse: collapse;
    font-family: 'Poppins', 'Inter', 'Segoe UI', sans-serif;
    font-size: 13px;
    color: #333;
    text-align: center;
    background-color: #fff;
}

.custom-table caption {
    caption-side: top;
    text-align: left;
    font-size: 16px;
    font-weight: 600;
    margin-bottom: 10px;
    color: #555;
}

.custom-table th,
.custom-table td {
    border: 1px solid #ddd;
    padding: 8px 12px;
}

.custom-table thead {
    background-color: #f0f2f5;
}

.custom-table tbody tr:hover {
    background-color: #f0f8ff;
}



.btn-edit, .btn-delete {
    background: none;
    border: none;
    cursor: pointer;
    font-size: 18px;
}

.btn-edit {
    color: #f0ad4e;
}

.btn-delete {
    color: #d9534f;
}

.no-data {
    text-align: center;
    padding: 20px;
    font-style: italic;
    color: #888;
}

.pagination-container {
    margin-top: 20px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.btn-primary {
    background-color: #1890ff;
    color: white;
    padding: 8px 16px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    font-size: 14px;
}

.btn-primary:hover {
    background-color: #40a9ff;
}

.pagination {
    display: flex;
    gap: 5px;
}

.pagination button {
    border: 1px solid #ddd;
    background-color: white;
    padding: 5px 10px;
    cursor: pointer;
}

.pagination button.active {
    background-color: #1890ff;
    color: white;
    border-color: #1890ff;
}

.pagination button:hover {
    background-color: #e6f7ff;
}

.total-info {
    font-size: 14px;
    color: #666;
}

    </style>
