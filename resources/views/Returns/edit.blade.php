<!DOCTYPE html>
<html lang="en">
@include('includes.header')

<body>
    <!--== MAIN CONTAINER ==-->
    @include('includes.topBar')

    <!--== BODY CONTAINER ==-->
    <div class="container-fluid sb2">
        <div class="row">
            <div class="sb2-1">
                @include('includes.sidebar')
            </div>
            <div class="sb2-2">
                <div class="sb2-2-2">
                    <ul>
                        <li><a href="#"><i class="fa fa-home" aria-hidden="true"></i> Home</a></li>
                        <li class="active-bre"><a href="#"> Edit Returns</a></li>
                    </ul>
                </div>
                <div class="sb2-2-3">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="box-inn-sp">
                                <div class="inn-title">
                                    <h4>Returns Edit</h4>
                                </div>
                                <div class="tab-inn">
                                    <div class="row">
                                        <div class="col-md-4"><b>Invoice Number:</b> {{ $returnProduct->invoice->invoice_number }}</div>
                                        <div class="col-md-4">
                                            <b>Total Amount:</b> <span id="totalAmountValue">{{ $returnItem->return_amount }}</span><br>
                                             <b>Discount:</b> <span>{{ $returnProduct->invoice->discount }}</span>
                                        </div>
                                        <div class="col-md-4"><b>This Invoice Total Amount:</b> {{ $returnProduct->total_amount }}</div>
                                    </div>
                                </div>
                                <div class="tab-inn">
                                    <form method="POST">
                                        @csrf
                                        @method('PUT')

                                        <div class="row">
                                            <!-- Salable Status -->
                                            <div class="input-field col s4">
                                                <select name="salable_status">
                                                    <option value="" disabled {{ old('salable_status', $returnItem->salable_status ?? '') == '' ? 'selected' : '' }}>Choose Status</option>
                                                    <option value="salable" {{ old('salable_status', $returnItem->salable_status ?? '') == 'salable' ? 'selected' : '' }}>Salable</option>
                                                    <option value="not_salable" {{ old('salable_status', $returnItem->salable_status ?? '') == 'not_salable' ? 'selected' : '' }}>Non-Salable</option>
                                                </select>
                                            </div>

                                            <!-- Reason Field -->
                                            <div class="input-field col s4">
                                                <label for="reason">Reason</label>
                                                <input type="text" id="reason" name="reason" value="{{ old('reason', $returnItem->reason ?? '') }}" />
                                            </div>

                                            <!-- Quantity Field -->
                                            <div class="input-field col s4">
                                                <label for="quantity">Quantity</label>
                                                <input type="text" id="quantity" name="quantity" value="{{ old('quantity', $returnItem->quantity ?? '') }}" oninput="updateTotalAmount()" />
                                            </div>

                                            <!-- Unit Price -->
                                            <div class="input-field col s4">
                                                <label for="sell_pricee">Unit Price</label>
                                                <input type="text" id="sell_pricee" name="sell_pricee" value="{{ old('sell_pricee', $productDetails->sell_price ?? '') }}" readonly />
                                            </div>
                                        </div>

                                        <button type="submit" class="btn btn-primary">Update</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function updateTotalAmount() {
            let quantity = document.getElementById("quantity").value;
            let unitPrice = document.getElementById("sell_pricee").value;
            
            // Ensure valid numbers for calculation
            quantity = parseFloat(quantity) || 0;
            unitPrice = parseFloat(unitPrice) || 0;

            let totalAmount = quantity * unitPrice;
            
            // Update only the numeric value inside the span
            document.getElementById("totalAmountValue").textContent = totalAmount.toFixed(2);
        }
    </script>

    @include('includes.js')
</body>
</html>
