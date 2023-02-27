
<div class="page page-back">
   <div class="projection-page page-back">
        <div class="p-p-text">
            Here is an example of the short term and long term rental projection for a 1 bedroom apartment:
        </div>
        <div class="p-p-short">
            <table>
                <tr>
                    <th>Short term rental projection</th>
                    <th>Ammount in AED</th>
                    <th>Ammount in USD</th>
                </tr>
                <tr>
                    <td>
                        Property Price
                    </td>
                    <td>
                        1000
                    </td>
                    <td>
                        3000
                    </td>
                </tr>
                <tr>
                    <td>
                        Minimum Rate per Night
                    </td>
                    <td>
                        1000
                    </td>
                    <td>
                        3000
                    </td>
                </tr>
                <tr>
                    <td>
                        Minimum Yearly Occupancy (80%)  = 292 Days
                    </td>
                    <td>
                        1000
                    </td>
                    <td>
                        3000
                    </td>
                </tr>
                <tr>
                    <td>
                        Short Term Holiday Homr Managment fee = 15% of  Profit
                    </td>
                    <td>
                        1000
                    </td>
                    <td>
                        3000
                    </td>
                </tr>
                <tr>
                    <td>
                        Yearly Service Chargr (Approximate)
                    </td>
                    <td>
                        1000
                    </td>
                    <td>
                        3000
                    </td>
                </tr>
                <tr>
                    <td>
                        Total Yearly Deductions
                    </td>
                    <td>
                        1000
                    </td>
                    <td>
                        3000
                    </td>
                </tr>
                <tr>
                    <td>
                        Net Profet After Deduction
                    </td>
                    <td>
                        1000
                    </td>
                    <td>
                        3000
                    </td>
                </tr>
                <tr>
                    <td>
                        Return on investment
                    </td>
                    <td colspan="2">
                        1000
                    </td>
                </tr>

            </table>
        </div>
        <div class="p-p-long">
            <table>
                <tr>
                    <th>Long term rental projection</th>
                    <th>Ammount in AED</th>
                    <th>Ammount in USD</th>
                </tr>
                <tr>
                    <td>
                        Property Price
                    </td>
                    <td>
                        1000
                    </td>
                    <td>
                        3000
                    </td>
                </tr>
                <tr>
                    <td>
                        Yearly Service Chargr (Approximate)
                    </td>
                    <td>
                        1000
                    </td>
                    <td>
                        3000
                    </td>
                </tr>
                <tr>
                    <td>
                        Net Profet After Deduction
                    </td>
                    <td>
                        1000
                    </td>
                    <td>
                        3000
                    </td>
                </tr>
                <tr>
                    <td>
                        Return on investment
                    </td>
                    <td colspan="2">
                        1000
                    </td>
                </tr>

            </table>
        </div>
   </div>
</div>


<style>
    *{
        margin: 0px;
        padding: 0px;
        /* box-sizing: border-box; */
        box-sizing: border-box;
    }
    @font-face {
        font-family: 'nova';
        src: url("{{ asset('fonts/Proxima nova/Proxima Nova Regular.otf') }}") format('truetype');
    }
    .page{
        overflow: hidden;
        height: 100vh;
    }
    .p-p-text{
        font-size: 18px;
    }
    .projection-page{
        width: calc(100vw - 40px);
        height: calc(100vh - 40px);
        display: flex;
        flex-direction: column;
        justify-content: space-evenly;
        align-items: center;
        font-family: 'nova';
        margin: 20px;
    }
    .p-p-long, .p-p-short{
        width: 60%;
        margin: 0 auto;
    }
    .projection-page table{
        border: 2px #000 solid;
        font-size: 16px;
        font-family: 'nova';
        width: 100%;
    }
    .projection-page table tr:first-of-type{
        background-color: #002d31;
        color: #fff;
    }
    .projection-page table tr:last-of-type{
        background-color: #002d31;
        color: #fff;
    }

    .projection-page table th,.projection-page table td{
        border-left: 2px #000 solid;
        border-bottom: 2px #000 solid;
        padding: 5px 5px;
        text-align: center;
    }
    .projection-page table th:first-of-type,.projection-page table td:first-of-type{
        border-left: none;
        text-align: left !important;
    }
    .projection-page table tr:last-of-type th,.projection-page table tr:last-of-type td{
        border-bottom: none;
    }



</style>
