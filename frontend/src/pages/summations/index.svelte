<script>
    // Menggunakan writable store untuk menyimpan nilai input form
    import moment from 'moment';
    import { onMount } from "svelte"
    import {Api} from "../../libs/api";
    import Swal from "sweetalert2";

    export let scoped = ''

    let button_close = '';
    let summations = [] 
    let destination_user_id = '';
    let sender_user_id = '';
    let total = '';
    let rate_per_unit = '';
    let bank = '';
    let account_number = '';
    let transfer_fees = '';
    let processed_by = '';
    let unixTimestamp = 1693987200;
    let idToUpdate = null //
    let total_page = 0
    let current_page = 0
    let pages = []

    
    $:total_price = total * rate_per_unit;
    $:total_pay = total_price - transfer_fees;

    getData()

    function resetValue() {
        destination_user_id = ''
        sender_user_id = ''
        total = ''
        rate_per_unit = ''
        bank = ''
        account_number = ''
        transfer_fees = ''
        processed_by = ''
        idToUpdate = null // meyimpan id jika updTE
        
    }

    // Fungsi untuk menangani submit
    async function handleSubmit() {
        let data = {
            total_price,
            total_pay,
            destination_user_id,
            sender_user_id,
            total,
            rate_per_unit,
            bank,
            account_number,
            transfer_fees,
            processed_by
        }
        
    
        try {
            if (idToUpdate){
                // Update data
                await Api.put('summations/' + idToUpdate, data)
            } else {
                await Api.post('summations', data);
            }

            const action = idToUpdate ? 'Diupdate' : 'Disimpan'
            Swal.fire({
                title: "aman boy!",
                text: "berrhasil" + action,
                icon: "success"
            });
            //tututp modal setelah berhasil submit
            button_close.click(); 
            getData()
        } catch (error) {
            console.log("errorna naon",error)
        }
        
        console.log({
            total_price,
            total_pay,
            destination_user_id,
            sender_user_id,
            total,
            rate_per_unit,
            bank,
            account_number,
            transfer_fees,
            processed_by
        })
        idToUpdate = null
    }

    //fungsi untuk edit data
    function editSummation(summation) {
        (destination_user_id = summation.destination_user_id),
        (sender_user_id = summation.sender_user_id),
        (total = summation.total),
        (rate_per_unit = summation.rate_per_unit),
        (bank = summation.bank),
        (account_number = summation.account_number),
        (transfer_fees = summation.transfer_fees),
        (processed_by = summation.processed_by),
        (total_price =  summation.total_price),
        (total_pay = summation.total_pay),
        //simpan id dari item yang akan di -update
        (idToUpdate = summation.id)

        const Modal = new bootstrap.Modal(document.getElementById('create-update-modal'))
        Modal.show()
    }

    async function getData(page = 1, search = '') {
            let res = await Api.get ('summations?page=' + page + '&search=' + search)
            console.log('response', res)
            summations = res.data.summations.data
            total_page = res.data.summations.last_page
            current_page = res.data.summations.current_page
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
            await Api.delete(`summations/${id}`);
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
<div class="d-flex border border-1 justify-content-end">
    <button type="button" class="btn btn-light " data-bs-toggle="modal" data-bs-target="#create-update-modal" on:click={resetValue}>
        <i class="fa-solid fa-plus"></i>
    </button>

</div>

<!-- Modal -->
<div class="modal fade" id="create-update-modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Modal</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" bind:this={button_close}></button>
      </div>
      <div class="modal-body">
        <div class="container">
        <div class="form-title">Transaksi pembayaran</div>
    <form on:submit={handleSubmit}>
            <!-- Basic Details Section -->
            <div class="mb-3">
                <label for="destination_User_Id" class="form-label">USERID TUJUAN</label>
                <input type="text" class="form-control" bind:value={destination_user_id} id="destination_user_id" placeholder="Enter UserId Tujuan " />
            </div>

            <div class="mb-3">
                <label for="sender_User_Id" class="form-label">UESRID PENGIRIM</label>
                <input type="text" class="form-control" bind:value={sender_user_id} id="sender_user_id" placeholder="Enter UserId pengirim " />
                
            </div>
            <div class="mb-3">
                <label for="total" class="form-label">Total</label>
                <input type="text" class="form-control" bind:value={total} id="total" placeholder="Enter total" />
            </div>
            <div class="mb-3">
                <label for="rate_Per_Unit" class="form-label">RATE PER UNIT (B)</label>
                <label for="rate_Per_Unit" class="form-label">contoh 34.000</label>
                <input type="text" class="form-control" bind:value={rate_per_unit} id="rate_per_unit" placeholder="Enter contoh 400000 " />
            </div>

            <div class="row">
                <div class="col-md-6 mb-3 gap-5">
                    <label for="bank" class="form-label">BANK</label>
                    <select class="form-select" bind:value={bank} id="bank">
                        <option value="" disabled hidden>Pilih Bank</option>
                        <option value="BRI">BRI</option>
                        <option value="BNI">BNI</option>
                        <option value="BCA">BCA</option>
                        <option value="MANDIRI">MANDIRI</option>
                        <option value="OVO">OVO</option>
                        <option value="GOPAY">GOPAY</option>
                        <option value="DANA">DANA</option>
                    </select>
                        
                        
                </div>
                
            
            <div class="mb-3">
                <label for="account_Number" class="form-label">NOMOR REKENING</label>
                <label for="account_Number" class="form-label">contoh 1234000093484</label>
                <input type="text" class="form-control" bind:value={account_number} id="account_number" placeholder="Enter contoh 15345534125145" />

            </div>
            <div class="mb-3">
                <label for="transfer_Fees" class="form-label">BIAYA TRANSFER</label>
                <label for="transfer_Fees" class="form-label">contoh 2.500</label>
                <input type="text" class="form-control" bind:value={transfer_fees} id="transfer_fees" placeholder="Enter contoh 2000" />
            </div>
            <div class="mb-3">
                <label for="prosessed_By" class="form-label">DIPROSES OLEH</label>
                <label for="prosessed_By" class="form-label">contoh anggi</label>
                <input type="text" class="form-control" bind:value={processed_by} id="processed_by" placeholder="Enter anggi" />
            </div>
          

        </form>
    </div> 
      </div>
        <!-- Save and Continue Button -->
      <div class="handleSubmit">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">close</button>
        <button type="button" class="btn btn-primary"on:click={handleSubmit}>Save changes</button>
      </div>
    </div>
  </div>
  n </div>



<!-- table data -->
 <table class="table caption -top">
    <caption>list of user</caption>
    <thead>
        <tr>
            <th scope="col">no</th>
            <th scope="col">id</th>
            <th scope="col">tanggal di buat</th>
            <th scope="col">user id tujuan</th>
            <th scope="col">user id pengirim</th>
            <th scope="col">jumlah</th>
            <th scope="col">rate per unit</th>
            <th scope="col">bank</th>
            <th scope="col">no.rekening</th>
            <th scope="col">biaya transfer</th>
            <th scope="col">total harga</th>
            <th scope="col">total bayar</th>
            <th scope="col">di proses oleh</th>
            <th scope="col">edit</th>
            <th scope="col">hapus</th>

        </tr>
    </thead>

    <tbody>
        {#if summations.length}
            {#each summations as summation, i}
                <tr>
                    <th scope="row">{i + 1}</th>
                    <th scope="row" style="font-size: 10pt;">{summation.id}</th>
                    <th scope="row">{formatDate(summation.created_at)}</th>
                    <th scope="row">{summation.sender_user_id}</th>
                    <th scope="row">{summation.destination_user_id}</th>
                    <th scope="row">{summation.total}</th>
                    <th scope="row">{summation.rate_per_unit}</th>
                    <th scope="row">{summation.bank}</th>
                    <th scope="row">{summation.account_number}</th>
                    <th scope="row">{summation.transfer_fees}</th>
                    <th scope="row">{summation.total_pay}</th>
                    <th scope="row">{summation.total_price}</th>
                    <th scope="row">{summation.processed_by}</th>
                    <td>
                        <button class="btn btn-warning btn-sm" on:click={() => editSummation(summation)}>edit</button>
                    </td>
                    <td>
                        <button class="btn btn-danger btn-sm" on:click={() => handleDelete(summation.id)}>Delete</button>
                    </td>
                </tr>
                <br/>
            {/each}
            <tr>
                <td colspan="5">Tidak ada data yang ditemukan.</td>
            </tr>
        {/if}
    </tbody>
</table>

<div class="d-flex justify-content-end">
        {#each pages as value}
        <button type="button" class="btn btn-sm mx-2 {value === current_page ? 'btn-primary' : ''}" on:click={() => getData(value)}>{value}</button>
    {/each}
</div>


<!-- style css -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
<style>
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
    </style>
