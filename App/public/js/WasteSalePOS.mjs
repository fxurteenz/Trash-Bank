/**
 * POS-Style Waste Sale Form Handler
 * Keyboard-optimized for fast data entry
 */

function WasteSalePOSHandler() {
    return {
        // Form state
        saleDate: new Date().toISOString().split('T')[0],
        items: [], // Array of {waste_type_id, weight, price}
        wasteTypes: [],
        wasteCategories: [],
        
        // UI state
        isLoading: false,
        isSubmitting: false,
        currentItemIndex: 0,
        showItemList: true,
        buyerName: '',
        errorMessage: '',
        successMessage: '',
        
        async init() {
            await this.fetchWasteTypes();
            await this.fetchWasteCategories();
        },
        
        async fetchWasteTypes() {
            try {
                const response = await fetch('/api/waste_types?limit=500');
                const result = await response.json();
                if (result.success) {
                    this.wasteTypes = result.result.data || result.result;
                }
            } catch (error) {
                console.error('Error fetching waste types:', error);
            }
        },
        
        async fetchWasteCategories() {
            try {
                const response = await fetch('/api/waste_categories?limit=500');
                const result = await response.json();
                if (result.success) {
                    this.wasteCategories = result.result.data || result.result;
                }
            } catch (error) {
                console.error('Error fetching categories:', error);
            }
        },
        
        getWasteTypeById(id) {
            return this.wasteTypes.find(t => t.waste_type_id === parseInt(id));
        },
        
        getWasteTypeByCode(code) {
            return this.wasteTypes.find(t => 
                t.waste_type_id.toString() === code || 
                t.waste_type_name.toLowerCase().includes(code.toLowerCase())
            );
        },
        
        addItem(typeId, weight, buyerName = null) {
            const type = this.getWasteTypeById(typeId);
            if (!type) {
                this.errorMessage = `ไม่พบประเภทขยะ ID: ${typeId}`;
                return false;
            }
            
            // Allow negative weight for cancellation
            if (weight < 0) {
                // Remove last item if weight is negative
                if (this.items.length > 0) {
                    this.items.pop();
                    this.successMessage = 'ยกเลิกรายการล่าสุด';
                }
                return true;
            }
            
            const item = {
                waste_sale_type_id: typeId,
                waste_sale_weight: parseFloat(weight).toFixed(3),
                waste_sale_actual_price: (parseFloat(weight) * parseFloat(type.waste_type_price)).toFixed(2),
                waste_sale_buyer: buyerName || this.buyerName,
                waste_sale_date: this.saleDate,
                wasteTypeName: type.waste_type_name,
                wasteTypePrice: type.waste_type_price
            };
            
            this.items.push(item);
            this.successMessage = `เพิ่ม ${item.wasteTypeName} ${item.waste_sale_weight} กก. เรียบร้อย`;
            
            return true;
        },
        
        removeItem(index) {
            this.items.splice(index, 1);
            this.successMessage = 'ลบรายการเรียบร้อย';
        },
        
        removeLastItem() {
            if (this.items.length > 0) {
                this.items.pop();
                this.successMessage = 'ยกเลิกรายการล่าสุด';
            }
        },
        
        clearItems() {
            this.items = [];
            this.successMessage = 'เคลียร์รายการเรียบร้อย';
        },
        
        getTotalWeight() {
            return this.items.reduce((sum, item) => sum + parseFloat(item.waste_sale_weight), 0).toFixed(3);
        },
        
        getTotalRevenue() {
            return this.items.reduce((sum, item) => sum + parseFloat(item.waste_sale_actual_price), 0).toFixed(2);
        },
        
        async submitBatch() {
            if (this.items.length === 0) {
                this.errorMessage = 'กรุณาเพิ่มรายการขยะ';
                return false;
            }
            
            this.isSubmitting = true;
            this.errorMessage = '';
            this.successMessage = '';
            
            try {
                const response = await fetch('/api/waste_sales/batch', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        sales: this.items
                    })
                });
                
                const result = await response.json();
                
                if (result.success) {
                    this.successMessage = `บันทึก ${result.result.created_count} รายการเรียบร้อย`;
                    this.items = [];
                    this.buyerName = '';
                    
                    // Reset form after 2 seconds
                    setTimeout(() => {
                        this.saleDate = new Date().toISOString().split('T')[0];
                        this.successMessage = '';
                    }, 2000);
                    
                    return true;
                } else {
                    this.errorMessage = result.message || 'เกิดข้อผิดพลาดในการบันทึก';
                    return false;
                }
            } catch (error) {
                console.error('Submit error:', error);
                this.errorMessage = 'เกิดข้อผิดพลาด: ' + error.message;
                return false;
            } finally {
                this.isSubmitting = false;
            }
        },
        
        async submitSingle(typeId, weight, buyerName) {
            if (this.addItem(typeId, weight, buyerName)) {
                return await this.submitBatch();
            }
            return false;
        }
    };
}

/**
 * POS Single Item Form Handler
 * Keyboard-focused entry for one item at a time
 */
function WasteSaleSingleFormHandler() {
    return {
        typeCode: '',
        weight: '',
        wasteTypes: [],
        wasteCategories: [],
        currentType: null,
        isLoading: false,
        isSubmitting: false,
        errorMessage: '',
        successMessage: '',
        saleDate: new Date().toISOString().split('T')[0],
        buyerName: '',
        
        async init() {
            await this.fetchWasteTypes();
            await this.fetchWasteCategories();
        },
        
        async fetchWasteTypes() {
            try {
                const response = await fetch('/api/waste_types?limit=500');
                const result = await response.json();
                if (result.success) {
                    this.wasteTypes = result.result.data || result.result;
                }
            } catch (error) {
                console.error('Error fetching waste types:', error);
            }
        },
        
        async fetchWasteCategories() {
            try {
                const response = await fetch('/api/waste_categories?limit=500');
                const result = await response.json();
                if (result.success) {
                    this.wasteCategories = result.result.data || result.result;
                }
            } catch (error) {
                console.error('Error fetching categories:', error);
            }
        },
        
        lookupWasteType(code) {
            // Try to match by ID first
            let type = this.wasteTypes.find(t => t.waste_type_id.toString() === code.trim());
            
            // Try to match by name
            if (!type) {
                type = this.wasteTypes.find(t => 
                    t.waste_type_name.toLowerCase().includes(code.toLowerCase())
                );
            }
            
            return type || null;
        },
        
        validateWeight(w) {
            const parsed = parseFloat(w);
            return !isNaN(parsed) && parsed > 0;
        },
        
        async submitSale() {
            this.errorMessage = '';
            this.successMessage = '';
            
            if (!this.typeCode.trim()) {
                this.errorMessage = 'กรุณากรอกรหัสประเภทขยะ';
                return false;
            }
            
            if (!this.validateWeight(this.weight)) {
                this.errorMessage = 'กรุณากรอกน้ำหนักที่ถูกต้อง (มากกว่า 0)';
                return false;
            }
            
            const type = this.lookupWasteType(this.typeCode);
            if (!type) {
                this.errorMessage = `ไม่พบประเภทขยะ: ${this.typeCode}`;
                return false;
            }
            
            this.isSubmitting = true;
            
            try {
                const saleData = {
                    waste_sale_type_id: type.waste_type_id,
                    waste_sale_weight: parseFloat(this.weight).toFixed(3),
                    waste_sale_actual_price: (parseFloat(this.weight) * parseFloat(type.waste_type_price)).toFixed(2),
                    waste_sale_buyer: this.buyerName || null,
                    waste_sale_date: this.saleDate
                };
                
                const response = await fetch('/api/waste_sales', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(saleData)
                });
                
                const result = await response.json();
                
                if (result.success) {
                    this.successMessage = `บันทึก ${type.waste_type_name} ${this.weight} กก. เรียบร้อย`;
                    this.typeCode = '';
                    this.weight = '';
                    
                    // Auto focus to typeCode field for next entry
                    setTimeout(() => {
                        document.querySelector('[x-ref="typeCodeInput"]')?.focus();
                        this.successMessage = '';
                    }, 500);
                    
                    return true;
                } else {
                    this.errorMessage = result.message || 'เกิดข้อผิดพลาดในการบันทึก';
                    return false;
                }
            } catch (error) {
                console.error('Submit error:', error);
                this.errorMessage = 'เกิดข้อผิดพลาด: ' + error.message;
                return false;
            } finally {
                this.isSubmitting = false;
            }
        }
    };
}

/**
 * Waste Sale History Handler
 */
function WasteSaleHistoryHandler() {
    return {
        sales: [],
        filteredSales: [],
        isLoading: false,
        page: 1,
        limit: 20,
        totalPages: 1,
        total: 0,
        
        // Filters
        filterDate: '',
        filterType: '',
        filterBuyer: '',
        
        // Sorting
        sortBy: 'waste_sale_date',
        sortDir: 'DESC',
        
        wasteTypes: [],
        
        async init() {
            await this.fetchWasteTypes();
            await this.loadSales();
        },
        
        async fetchWasteTypes() {
            try {
                const response = await fetch('/api/waste_types?limit=500');
                const result = await response.json();
                if (result.success) {
                    this.wasteTypes = result.result.data || result.result;
                }
            } catch (error) {
                console.error('Error fetching waste types:', error);
            }
        },
        
        async loadSales() {
            this.isLoading = true;
            
            try {
                const params = new URLSearchParams({
                    page: this.page,
                    limit: this.limit
                });
                
                if (this.filterDate) params.append('date', this.filterDate);
                if (this.filterType) params.append('waste_type_id', this.filterType);
                if (this.filterBuyer) params.append('buyer_search', this.filterBuyer);
                
                const response = await fetch(`/api/waste_sales?${params}`);
                const result = await response.json();
                
                if (result.success) {
                    if (result.result.data) {
                        // Paginated result
                        this.sales = result.result.data;
                        this.total = result.result.pagination.total;
                        this.totalPages = result.result.pagination.totalPages;
                    } else {
                        // Non-paginated result
                        this.sales = result.result;
                        this.total = result.result.length;
                        this.totalPages = Math.ceil(this.total / this.limit);
                    }
                    
                    this.filteredSales = this.sales;
                }
            } catch (error) {
                console.error('Error loading sales:', error);
            } finally {
                this.isLoading = false;
            }
        },
        
        async deleteSale(saleId) {
            if (confirm('คุณแน่ใจหรือว่าต้องการลบรายการนี้?')) {
                try {
                    const response = await fetch(`/api/waste_sales/delete/${saleId}`, {
                        method: 'POST'
                    });
                    
                    const result = await response.json();
                    if (result.success) {
                        await this.loadSales();
                    }
                } catch (error) {
                    console.error('Error deleting sale:', error);
                }
            }
        },
        
        applyFilters() {
            this.page = 1;
            this.loadSales();
        },
        
        clearFilters() {
            this.filterDate = '';
            this.filterType = '';
            this.filterBuyer = '';
            this.page = 1;
            this.loadSales();
        },
        
        async nextPage() {
            if (this.page < this.totalPages) {
                this.page++;
                await this.loadSales();
            }
        },
        
        async prevPage() {
            if (this.page > 1) {
                this.page--;
                await this.loadSales();
            }
        },
        
        getWasteTypeName(typeId) {
            return this.wasteTypes.find(t => t.waste_type_id === typeId)?.waste_type_name || `ID: ${typeId}`;
        },
        
        getTotalWeight() {
            return this.sales.reduce((sum, sale) => sum + parseFloat(sale.waste_sale_weight || 0), 0).toFixed(3);
        },
        
        getTotalRevenue() {
            return this.sales.reduce((sum, sale) => sum + parseFloat(sale.waste_sale_actual_price || 0), 0).toFixed(2);
        }
    };
}
